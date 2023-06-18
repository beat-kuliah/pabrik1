<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendor = Vendor::all();

        $data = [
            'status' => 200,
            'vendor' => $vendor,
        ];

        return view('vendor.index');
    }

    public function store(Request $request)
    {
        $check = Vendor::where('kode', '=', $request->kode_barang)->get();

        if (count($check) == 0) {
            $vendor = new Vendor();
            $vendor->kode = $request->kode_barang;
            $vendor->nama = $request->nama;
            $vendor->save();

            $status = 200;
        } else {
            $status = 500;
        }

        $result = [
            'status' => $status,
        ];

        return $result;
    }

    public function destroy($id)
    {
        $vendor = Vendor::find($id);
        $vendor->delete();

        $result = [
            'status' => 200,
        ];

        return $result;
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
        $totalRecords = Vendor::select('count(*) as allcount')->count();

        // Fetch records
        $records = Vendor::orderBy($columnName, $columnSortOrder)
            ->where('vendor.nama', 'like', '%' . $searchValue . '%')
            ->orWhere('vendor.kode', 'like', '%' . $searchValue . '%')
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
            $created_at = $record->created_at;
            $updated_at = $record->updated_at;

            $data_arr[] = array(
                "id" => $id,
                "kode" => $kode,
                "nama" => $nama,
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

    public function getAll()
    {
        $vendor = Vendor::all();

        return $vendor;
    }
}
