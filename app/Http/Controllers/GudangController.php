<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index()
    {
        $gudang = Gudang::all();

        $data = [
            'status' => 200,
            'gudang' => $gudang,
        ];

        return view('gudang.index');
    }

    public function store(Request $request)
    {
        $check = Gudang::where('kode', '=', $request->kode_barang)->get();

        if (count($check) == 0) {
            $gudang = new Gudang();
            $gudang->kode = $request->kode_barang;
            $gudang->nama = $request->nama;
            $gudang->vendor_id = $request->vendor;
            $gudang->save();

            if ($request->alamat1 != null) {
                $alamat = new Alamat();
                $alamat->gudang_id = $gudang->id;
                $alamat->alamat = $request->alamat1;
                $alamat->save();
            }
            if ($request->alamat2 != null) {
                $alamat = new Alamat();
                $alamat->gudang_id = $gudang->id;
                $alamat->alamat = $request->alamat2;
                $alamat->save();
            }
            if ($request->alamat3 != null) {
                $alamat = new Alamat();
                $alamat->gudang_id = $gudang->id;
                $alamat->alamat = $request->alamat3;
                $alamat->save();
            }


            $status = 200;
        } else {
            $status = 500;
        }

        $data = [
            'status' => $status
        ];

        return $data;
    }

    public function show($id)
    {
        $gudang = Gudang::find($id);

        $data = [
            'status' => 200,
            'gudang' => $gudang
        ];

        return $data;
    }

    public function update($id, Request $request)
    {
        $check = Gudang::where('kode', '=', $request->kode_barang)->first();
        $gudang = Gudang::find($id);

        if ($check != null && $gudang->kode != $check->kode)
            return 500;

        $gudang->kode = $request->kode_barang;
        $gudang->nama = $request->nama;
        $alamats = Alamat::where('gudang_id', '=', $id)->get();

        if (count($alamats) >= 1) {
            if ($request->alamat1 != null) {
                $alamat = Alamat::find($alamats[0]->id);
                if ($alamat == null)
                    $alamat = new Alamat();
                $alamat->gudang_id = $id;
                $alamat->alamat = $request->alamat1;
                $alamat->save();
            } else {
                $alamat = Alamat::find($alamats[2]->id);
                $alamat->delete();
            }
        }
        if (count($alamats) >= 2) {
            if ($request->alamat2 != null) {
                $alamat = Alamat::find($alamats[1]->id);
                if ($alamat == null)
                    $alamat = new Alamat();
                $alamat->gudang_id = $id;
                $alamat->alamat = $request->alamat2;
                $alamat->save();
            } else {
                $alamat = Alamat::find($alamats[2]->id);
                $alamat->delete();
            }
        }
        if (count($alamats) >= 3) {
            if ($request->alamat3 != null) {
                $alamat = Alamat::find($alamats[2]->id);
                if ($alamat == null)
                    $alamat = new Alamat();
                $alamat->gudang_id = $id;
                $alamat->alamat = $request->alamat3;
                $alamat->save();
            } else {
                $alamat = Alamat::find($alamats[2]->id);
                $alamat->delete();
            }
        }
        $gudang->save();

        return 200;
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
        $totalRecords = Gudang::select('count(*) as allcount')->count();

        // Fetch records
        $records = Gudang::orderBy($columnName, $columnSortOrder)
            ->where('gudang.nama', 'like', '%' . $searchValue . '%')
            ->orWhere('gudang.kode', 'like', '%' . $searchValue . '%')
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
            $vendor = $record->vendor->nama;
            $created_at = $record->created_at;
            $updated_at = $record->updated_at;

            $data_arr[] = array(
                "id" => $id,
                "kode" => $kode,
                "nama" => $nama,
                'vendor' => $vendor,
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
        $gudang = Gudang::all();

        return $gudang;
    }

    public function destroy($id)
    {
        $gudang = Gudang::find($id);
        $barang = Barang::where('gudang_id', '=', $id)->get();
        foreach ($barang as $b) {
            $penjualan = Penjualan::where('barang_id', '=', $b->id)->delete();
        }
        $barang = Barang::where('gudang_id', '=', $id)->delete();
        $gudang->delete();
    }
}
