<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function indexStok()
    {
        return view('report.stok');
    }

    public function indexAccounting()
    {
        return view('report.penjualan');
    }

    public function stokGeneratePDF($from, $to, $gudang)
    {
        if ($gudang != 'null') {
            $records = Barang::where('gudang_id', '=', $gudang)->get();
            $gudang = Gudang::find($gudang)->nama;
        } else {
            $records = Barang::all();
            $gudang = 'Semua';
        }

        $data_arr = array();
        foreach ($records as $record) {
            $terjual_akhir = DB::select('select SUM(terjual) as terjual from penjualan where barang_id = ' . $record->id . ' AND tanggal > "' . $to . '"')[0]->terjual;
            $terjual_proses = (int)DB::select('select SUM(terjual) as terjual from penjualan where barang_id = ' . $record->id . ' AND tanggal >= "' . $from . '" AND tanggal <= "' . $to . '" AND created_at >= "' . $record->updated_at . '"')[0]->terjual;
            $id = $record->id;
            $kode = $record->kode;
            $nama = $record->nama;
            $harga = $record->harga;
            $stok_akhir = $record->stok_akhir + $terjual_akhir;
            $stok_awal = $stok_akhir + $terjual_proses;
            $penjualan = $terjual_proses;

            $data_arr[] = array(
                "id" => $id,
                "kode" => $kode,
                "nama" => $nama,
                "harga" => $harga,
                "stok_awal" => $stok_awal,
                "penjualan" => $penjualan,
                "stok_akhir" => $stok_akhir,
                "action" => $id,
            );
        }

        $data = [
            'barang' => $data_arr,
            'from' => $this->fixDateOnly($from),
            'to' => $this->fixDateOnly($to),
            'gudang' => $gudang,
            'dibuat' => $this->fixDateOnly(date('Y-m-d')) . date("H:i:s"),
        ];

        $pdf = Pdf::loadView('pdf.stok_report', $data);
        $pdf->set_paper('letter', 'landscape');
        $pdf->set_base_path(__DIR__);
        $pdf->render();
        return $pdf->stream('invoice.pdf');
    }

    public function stokDatatables(Request $request)
    {
        $from = $request[0]['value'];
        $to = $request[1]['value'];
        $gudang = $request[2]['value'];
        $draw = (int)$request->get('draw');
        $start = (int)$request->get("start");
        $rowperpage = (int)$request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Barang::select('count(*) as allcount')->count();
        // $searchValue = 'z';
        // Fetch records
        $records = Barang::orderBy($columnName, $columnSortOrder)
            ->where([['barang.nama', 'like', '%' . $searchValue . '%'], ['barang.kode', 'like', '%' . $searchValue . '%']])
            ->where('barang.gudang_id', '=', $gudang)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $check = Barang::orderBy($columnName, $columnSortOrder)
            ->where([['barang.nama', 'like', '%' . $searchValue . '%'], ['barang.kode', 'like', '%' . $searchValue . '%']])
            ->where('barang.gudang_id', '=', $gudang)
            ->get();
        $totalRecordswithFilter = count($check);

        // return $penjualan;
        $data_arr = array();
        foreach ($records as $record) {
            $terjual_akhir = DB::select('select SUM(terjual) as terjual from penjualan where barang_id = ' . $record->id . ' AND tanggal > "' . $from . '" AND tanggal = "' . $to . '"')[0]->terjual;
            $terjual_proses = 'select SUM(terjual) as terjual from penjualan where barang_id = ' . $record->id . ' AND created_at >= "' . $record->updated_at . '"';
            if ($from != '')
                $terjual_proses .= ' AND tanggal >= "' . $from . '"';
            if ($to != '')
                $terjual_proses .= ' AND tanggal <= "' . $to . '"';
            $terjual_proses = (int)DB::select($terjual_proses)[0]->terjual;
            if ($terjual_akhir == null)
                $terjual_akhir = 0;
            if ($terjual_proses == null)
                $terjual_proses = 0;

            $id = $record->id;
            $kode = $record->kode;
            $nama = $record->nama;
            $harga = $record->harga;
            if ($gudang == 1) {
                $stok_akhir = $record->stok_akhir;
                $stok_awal = $record->stok_awal;
                $penjualan = $record->stok_awal - $record->stok_akhir;
            } else {
                $stok_akhir = $record->stok_akhir + $terjual_akhir;
                $stok_awal = $stok_akhir + $terjual_proses;
                $penjualan = $terjual_proses;
            }
            $data_arr[] = array(
                "id" => $id,
                "kode" => $kode,
                "nama" => $nama,
                "harga" => $harga,
                "stok_awal" => $stok_awal,
                "penjualan" => $penjualan,
                "stok_akhir" => $stok_akhir,
                "action" => $id,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return json_encode($response);
    }

    public function penjualanDatatables(Request $request)
    {
        $from = $request[0]['value'];
        $to = $request[1]['value'];
        $gudang = $request[2]['value'];
        $draw = (int)$request->get('draw');
        $start = (int)$request->get("start");
        $rowperpage = (int)$request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Barang::select('count(*) as allcount')->count();
        // $searchValue = 'z';
        // Fetch records
        if ($gudang != '') {
            $records = Penjualan::orderBy($columnName, $columnSortOrder)
                ->where([['barang.nama', 'like', '%' . $searchValue . '%'], ['barang.kode', 'like', '%' . $searchValue . '%']])
                ->Where('gudang.id', '=', $gudang)
                ->whereBetween('penjualan.tanggal', [$from, $to])
                ->select('penjualan.*')
                ->join('barang', 'barang.id', '=', 'penjualan.barang_id')
                ->join('gudang', 'gudang.id', '=', 'barang.gudang_id')
                ->skip($start)
                ->take($rowperpage)
                ->get();

            $check = Penjualan::orderBy($columnName, $columnSortOrder)
                ->where([['barang.nama', 'like', '%' . $searchValue . '%'], ['barang.kode', 'like', '%' . $searchValue . '%']])
                ->Where('gudang.id', '=', $gudang)
                ->whereBetween('penjualan.tanggal', [$from, $to])
                ->select('penjualan.*')
                ->join('barang', 'barang.id', '=', 'penjualan.barang_id')
                ->join('gudang', 'gudang.id', '=', 'barang.gudang_id')
                ->get();
        } else {
            $records = Penjualan::orderBy($columnName, $columnSortOrder)
                ->where([['barang.nama', 'like', '%' . $searchValue . '%'], ['barang.kode', 'like', '%' . $searchValue . '%']])
                ->whereBetween('penjualan.tanggal', [$from, $to])
                ->select('penjualan.*')
                ->join('barang', 'barang.id', '=', 'penjualan.barang_id')
                ->skip($start)
                ->take($rowperpage)
                ->get();

            $check = Penjualan::orderBy($columnName, $columnSortOrder)
                ->where([['barang.nama', 'like', '%' . $searchValue . '%'], ['barang.kode', 'like', '%' . $searchValue . '%']])
                ->whereBetween('penjualan.tanggal', [$from, $to])
                ->select('penjualan.*')
                ->join('barang', 'barang.id', '=', 'penjualan.barang_id')
                ->get();
        }
        $totalRecordswithFilter = count($check);

        // return $penjualan;
        $data_arr = array();
        $counter = 1;
        $full = 0;
        foreach ($records as $record) {
            $id = $counter;
            $tanggal = $record->tanggal;
            $kode = $record->barang->kode;
            $nama = $record->barang->nama;
            $harga = $record->barang->harga;
            $qty = $record->terjual;
            $total = $record->terjual * $harga;

            $data_arr[] = array(
                "id" => $id,
                "tanggal" => $tanggal,
                "kode" => $kode,
                "nama" => $nama,
                "harga" => $harga,
                "qty" => $qty,
                "total" => $this->fixPrice($total),
            );
            $full += $total;
            $counter++;
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
            "full" => $this->fixPrice($full),
        );

        return json_encode($response);
    }

    public function penjualanGeneratePDF($from, $to, $gudang)
    {
        if ($gudang != 'null') {
            $records = Penjualan::where('gudang.id', '=', $gudang)
                ->whereBetween('penjualan.tanggal', [$from, $to])
                ->select('penjualan.*')
                ->join('barang', 'barang.id', '=', 'penjualan.barang_id')
                ->join('gudang', 'gudang.id', '=', 'barang.gudang_id')
                ->get();
        } else {
            $gudang = 'Semua';
            $records = Penjualan::whereBetween('penjualan.tanggal', [$from, $to])
                ->select('penjualan.*')
                ->join('barang', 'barang.id', '=', 'penjualan.barang_id')
                ->get();
        }

        $data_arr = array();
        $counter = 1;
        $full = 0;
        foreach ($records as $record) {
            $id = $counter;
            $tanggal = $record->tanggal;
            $kode = $record->barang->kode;
            $nama = $record->barang->nama;
            $harga = $record->barang->harga;
            $qty = $record->terjual;
            $total = $record->terjual * $harga;

            $data_arr[] = array(
                "id" => $id,
                "tanggal" => $tanggal,
                "kode" => $kode,
                "nama" => $nama,
                "harga" => $this->fixPrice($harga),
                "qty" => $qty,
                "total" => $this->fixPrice($total),
            );
            $full += $total;
            $counter++;
        }

        $data = [
            'penjualan' => $data_arr,
            'tanggal' => $this->fixDateOnly($from) . ' - ' . $this->fixDateOnly($to),
            'gudang' => $gudang,
            'dibuat' => $this->fixDateOnly(date('Y/m/d')) . ' ' . date("H:i:s"),
            'full' => $this->fixPrice($full),
        ];

        $pdf = Pdf::loadView('pdf.penjualan_report', $data);
        $pdf->set_paper('letter', 'landscape');
        $pdf->set_base_path(__DIR__);
        $pdf->render();
        return $pdf->stream('invoice.pdf');
    }
}
