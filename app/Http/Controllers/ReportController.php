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

    public function stokGeneratePDF($tanggal, $gudang)
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
            $terjual_akhir = DB::select('select SUM(terjual) as terjual from penjualan where barang_id = ' . $record->id . ' AND tanggal > "' . $tanggal . '"')[0]->terjual;
            $terjual_proses = (int)DB::select('select SUM(terjual) as terjual from penjualan where barang_id = ' . $record->id . ' AND tanggal = "' . $tanggal . '" AND created_at >= "' . $record->updated_at . '"')[0]->terjual;
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
            'tanggal' => $this->fixDateOnly($tanggal),
            'gudang' => $gudang,
            'dibuat' => date("H:i:s"),
        ];

        $pdf = Pdf::loadView('pdf.stok_report', $data);
        $pdf->set_paper('letter', 'landscape');
        $pdf->set_base_path(__DIR__);
        $pdf->render();
        return $pdf->stream('invoice.pdf');
    }

    public function stokDatatables($tanggal, $gudang, Request $request)
    {
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
        if ($gudang != 'null') {
            $records = Barang::orderBy($columnName, $columnSortOrder)
                ->where([['barang.nama', 'like', '%' . $searchValue . '%'], ['barang.kode', 'like', '%' . $searchValue . '%']])
                ->where('barang.gudang_id', '=', $gudang)
                ->skip($start)
                ->take($rowperpage)
                ->get();
        } else {
            $records = Barang::orderBy($columnName, $columnSortOrder)
                ->where([['barang.nama', 'like', '%' . $searchValue . '%'], ['barang.kode', 'like', '%' . $searchValue . '%']])
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }
        $totalRecordswithFilter = count($records);

        // return $penjualan;
        $data_arr = array();
        foreach ($records as $record) {
            $terjual_akhir = DB::select('select SUM(terjual) as terjual from penjualan where barang_id = ' . $record->id . ' AND tanggal > "' . $tanggal . '"')[0]->terjual;
            $terjual_proses = (int)DB::select('select SUM(terjual) as terjual from penjualan where barang_id = ' . $record->id . ' AND tanggal = "' . $tanggal . '" AND created_at >= "' . $record->updated_at . '"')[0]->terjual;
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

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return json_encode($response);
    }
}
