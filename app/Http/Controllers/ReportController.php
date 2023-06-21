<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.index');
    }

    public function generatePDF()
    {
        $barang = Barang::all();

        $data = [
            'barang' => $barang,
        ];

        $pdf = Pdf::loadView('pdf.report', $data);
        $pdf->set_paper('letter', 'landscape');
        $pdf->set_base_path(__DIR__);
        $pdf->render();
        return $pdf->stream('invoice.pdf');
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
        $totalRecords = Barang::select('count(*) as allcount')->count();
        // $searchValue = 'z';
        // Fetch records
        $records = Barang::orderBy($columnName, $columnSortOrder)
            ->where([['barang.nama', 'like', '%' . $searchValue . '%'], ['barang.kode', 'like', '%' . $searchValue . '%']])
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $totalRecordswithFilter = count($records);

        // return $penjualan;
        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $kode = $record->kode;
            $nama = $record->nama;
            $harga = $record->harga;
            $stok_awal = $record->stok_awal;
            $stok_akhir = $record->stok_akhir;

            $data_arr[] = array(
                "id" => $id,
                "kode" => $kode,
                "nama" => $nama,
                "harga" => $harga,
                "stok_awal" => $stok_awal,
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
