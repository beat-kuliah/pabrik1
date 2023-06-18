<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::all();

        $data = [
            'status' => 200,
            'penjualan' => $penjualan,
        ];

        return view('penjualan.index');
    }

    public function store(Request $request)
    {
        $barang = new Penjualan();
        $barang->barang_id;
        $barang->terjual;
        $barang->tanggal;
        $barang->save();

        return 0;
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

        // Total records
        $totalRecords = Penjualan::select('count(*) as allcount')->count();

        // Fetch records
        $records = Penjualan::orderBy($columnName, $columnSortOrder)
            ->where('barang.nama', 'like', '%' . $searchValue . '%')
            ->orWhere('barang.kode', 'like', '%' . $searchValue . '%')
            ->select('penjualan.*')
            ->join('barang', 'penjualan.barang_id', '=', 'barang.id')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $totalRecordswithFilter = count($records);

        // dd(json_encode($records));
        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $kode = $record->barang->kode;
            $nama = $record->barang->nama;
            $harga = $record->barang->harga;
            $jumlah = $record->terjual;
            $total = $record->terjual * $record->barang->harga;
            $tanggal = $record->tanggal;

            $data_arr[] = array(
                "id" => $id,
                "kode" => $kode,
                "nama" => $nama,
                "harga" => $harga,
                "jumlah" => $jumlah,
                "total" => $total,
                "tanggal" => $tanggal,
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
