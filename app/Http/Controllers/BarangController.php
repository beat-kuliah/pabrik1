<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Penjualan;
use App\Models\Vendor;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $gudang = Gudang::all();
        $vendor = Vendor::all();

        $data = [
            'status' => 200,
            'gudang' => $gudang,
            'vendor' => $vendor,
        ];

        return view('barang.index', $data);
    }

    public function store(Request $request)
    {
        $check = Barang::where('kode', '=', $request->kode_barang)->get();

        if (count($check) == 0) {
            $barang = new Barang();
            $barang->kode = $request->kode_barang;
            $barang->nama = $request->nama;
            $barang->stok_awal = $request->stok;
            $barang->stok_akhir = $request->stok;
            $barang->harga  = $request->harga;
            $barang->gudang_id = $request->gudang;
            $barang->save();

            $status = 200;
        } else {
            $status = 500;
        }

        $data = [
            'status' => $status,
        ];

        return $data;
    }

    public function update($id, Request $request)
    {
        $check = Barang::where('kode', '=', $request->kode_barang)->first();
        $barang = Barang::find($id);

        if ($check != null && $barang->kode != $check->kode)
            return 500;

        $barang->kode = $request->kode_barang;
        $barang->nama = $request->nama;
        $barang->harga = $request->harga;
        $barang->gudang_id = $request->vendor;
        $barang->save();

        return 200;
    }

    public function updateStok($id, Request $request)
    {
        $barang = Barang::find($id);
        if ($barang->gudang_id == 2) {
            $barangUtama = Barang::where('gudang_id', '=', 1)->where('kode', '=', $barang->kode)->first();
            $barangUtama->stok_akhir = $barangUtama->stok_awal - $request->stok;
            $barangUtama->save();
        }
        $stok = $barang->stok_akhir + $request->stok;
        $barang->stok_awal = $stok;
        $barang->stok_akhir = $stok;
        $barang->save();

        $data = [
            'status' => 200,
        ];

        return $data;
    }

    public function findOne($id)
    {
        $barang = Barang::find($id);

        return $barang;
    }

    public function getAll()
    {
        $barang = Barang::all();

        return $barang;
    }

    public function getAllGudang($gudang)
    {
        $barang = Barang::where('gudang_id', '=', 2)->get();

        return $barang;
    }

    public function datatables(Request $request)
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
        $searchRole = $columnName_arr[4]['search']['value'];

        // Total records
        $totalRecords = Barang::select('count(*) as allcount')->count();

        // Fetch records
        if (isset($request->gudangFilter)) {
            $records = Barang::orderBy($columnName, $columnSortOrder)
                ->where([['barang.nama', 'like', '%' . $searchValue . '%'], ['barang.kode', 'like', '%' . $searchValue . '%']])
                ->where('barang.gudang_id', '=', $request->gudangFilter)
                ->skip($start)
                ->take($rowperpage)
                ->get();
        } else {
            $records = Barang::orderBy($columnName, $columnSortOrder)
                ->where('barang.nama', 'like', '%' . $searchValue . '%')
                ->orWhere('barang.kode', 'like', '%' . $searchValue . '%')
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }

        $check = Barang::orderBy($columnName, $columnSortOrder)
            ->where('barang.nama', 'like', '%' . $searchValue . '%')
            ->orWhere('barang.kode', 'like', '%' . $searchValue . '%')
            ->get();
        $totalRecordswithFilter = count($check);

        // dd($records);
        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $kode = $record->kode;
            $nama = $record->nama;
            $stok_awal = $record->stok_awal;
            $stok_akhir = $record->stok_akhir;
            $harga = $record->harga;
            $vendor = $record->gudang->vendor->nama;
            $gudang = $record->gudang->nama;

            $data_arr[] = array(
                "id" => $id,
                "kode" => $kode,
                "nama" => $nama,
                "stock_awal" => $stok_awal,
                "stock_akhir" => $stok_akhir,
                "harga" => $harga,
                "vendor" => $vendor,
                "gudang" => $gudang,
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

    public function destroy($id)
    {
        $barang = Barang::find($id);
        $penjualan = Penjualan::where('barang_id', '=', $id)->delete();
        $barang->delete();

        return 200;
    }
}
