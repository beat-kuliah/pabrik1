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
}
