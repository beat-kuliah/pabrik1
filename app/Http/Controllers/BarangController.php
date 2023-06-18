<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gudang;
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
            $barang->vendor_id = $request->vendor;
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

    public function updateStok($id, Request $request)
    {
        $barang = Barang::find($id);
        $stok = $barang->stok_akhir + $request->stok;
        $barang->stok_awal = $stok;
        $barang->stok_akhir = $stok;
        $barang->save();

        $data = [
            'status' => 200,
        ];

        return $data;
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
        $records = Barang::orderBy($columnName, $columnSortOrder)
            ->where('barang.nama', 'like', '%' . $searchValue . '%')
            ->orWhere('barang.kode', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $totalRecordswithFilter = count($records);

        // dd($records);
        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $kode = $record->kode;
            $nama = $record->nama;
            $stok_awal = $record->stok_awal;
            $stok_akhir = $record->stok_akhir;
            $harga = $record->harga;
            $vendor = $record->vendor->nama;
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
}
