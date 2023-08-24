<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\Retur;
use Illuminate\Http\Request;

class ReturController extends Controller
{
    public function index()
    {
        $retur = Retur::first();

        $data = [
            'retur' => $retur,
        ];

        return view('retur.index', $data);
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
        $totalRecords = Retur::select('count(*) as allcount')->count();
        $totalRecordswithFilter = 0;

        // Fetch records
        $records = Retur::orderBy($columnName, $columnSortOrder)
            ->where([['retur.oldId', 'like', '%' . $searchValue . '%'], ['retur.total_retur', 'like', '%' . $searchValue . '%']])
            ->skip($start)
            ->take($rowperpage)
            ->get();
        // dd($records);
        $totalRecordswithFilter = sizeof($records);
        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $oldId = $record->oldId;
            $total_retur = $record->total_retur;
            $created_at = $record->created_at;
            $updated_at = $record->updated_at;

            $data_arr[] = array(
                "id" => $id,
                "oldId" => $oldId,
                "total_retur" => $total_retur,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
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

    public function store(Request $request)
    {
        $penjualanId = $request->barangId;
        $total_retur = $request->jmlretur;
        $penjualan = Penjualan::find($penjualanId);

        $retur = new Retur();
        $retur->oldId = $penjualanId;
        $retur->total_retur = $total_retur;
        $retur->save();

        $barang = Barang::find($penjualan->barang_id);
        $barang->stok_akhir = $barang->stok_akhir - $total_retur;
        $barang->save();

        return 200;
    }

    public function destroy($id)
    {
        $retur = Retur::find($id);
        $penjualan = Penjualan::find($retur->oldId);
        $barang = Barang::find($penjualan->barang_id);
        $final_stok = $barang->stok_akhir + $retur->total_retur;
        if ($barang->stok_awal < $final_stok) {
            $barang->stok_awal = $final_stok;
        }
        $barang->stok_akhir = $final_stok;
        $barang->save();

        $retur->delete();
    }

    public function showPenjualan($id)
    {
        $retur = Retur::where('oldId', '=', $id)->first();

        return $retur;
    }

    public function show($id)
    {
        $retur = Retur::find($id);

        return $retur;
    }

    public function update(Request $request, $id)
    {
        $total_retur = $request->total_retur;
        $retur = Retur::find($id);
        $barang = Barang::find($retur->penjualan->barang_id);
        $last_diff = 0;

        if ($total_retur > $retur->total_retur) {
            $last_diff = $barang->stok_akhir - ($total_retur - $retur->total_retur);
        } else {
            $last_diff = $barang->stok_akhir + ($retur->total_retur - $total_retur);
        }

        $retur->total_retur = $total_retur;
        $retur->save();

        if ($barang->stok_awal < $last_diff)
            $barang->stok_awal = $last_diff;
        $barang->stok_akhir = $last_diff;
        $barang->save();

        return 200;
    }
}
