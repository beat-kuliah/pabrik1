<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [[
            'kode' => 'TMD05',
            'nama' => 'TRACE MINERAL DROP 0,5 OZ',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 75000,
            'gudang_id' => 1
        ], [
            'kode' => 'TMD1',
            'nama' => 'TRACE MINERAL DROP 1 OZ',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 125000,
            'gudang_id' => 1
        ], [
            'kode' => 'TMD2',
            'nama' => 'TRACE MINERAL DROP 2 OZ',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 185000,
            'gudang_id' => 1
        ], [
            'kode' => 'TMD4',
            'nama' => 'TRACE MINERAL DROP 4 OZ',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 310000,
            'gudang_id' => 1
        ], [
            'kode' => 'TMD8',
            'nama' => 'TRACE MINERAL DROP 8 OZ',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 425000,
            'gudang_id' => 1
        ], [
            'kode' => 'ECM30',
            'nama' => 'E-CHARGED MULTI 30 TABS',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 195000,
            'gudang_id' => 1
        ], [
            'kode' => 'TMT30',
            'nama' => 'TRACE MINERAL TABLETS 30 TABS',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 185000,
            'gudang_id' => 1
        ], [
            'kode' => 'ARX30',
            'nama' => 'ARTH-X BONE 30 TABS',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 250000,
            'gudang_id' => 1
        ], [
            'kode' => 'DMB30',
            'nama' => 'FEED MYBRAIN 30 TABS',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 165000,
            'gudang_id' => 1
        ], [
            'kode' => 'FMB60',
            'nama' => 'FEED MYBRAIN 60 TABS',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 310000,
            'gudang_id' => 1
        ], [
            'kode' => 'CCC90',
            'nama' => 'COLON CARE CAPS 90 TABS',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 240000,
            'gudang_id' => 1
        ], [
            'kode' => 'CCC30',
            'nama' => 'RENEWAL CAPS 30 TABS',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 275000,
            'gudang_id' => 1
        ], [
            'kode' => 'TMS02',
            'nama' => 'TRACE MINERAL ALOEVERA SOAP',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 45000,
            'gudang_id' => 1
        ], [
            'kode' => 'FDC01',
            'nama' => 'FRESH DEODORANT CRYSTAL',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 26000,
            'gudang_id' => 1
        ], [
            'kode' => 'CWS01',
            'nama' => 'COLLAGEN SOAP',
            'stok_awal' => 200,
            'stok_akhir' => 200,
            'harga' => 70000,
            'gudang_id' => 1
        ], [
            'kode' => 'TMD05',
            'nama' => 'TRACE MINERAL DROP 0,5 OZ',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 75000,
            'gudang_id' => 2
        ], [
            'kode' => 'TMD1',
            'nama' => 'TRACE MINERAL DROP 1 OZ',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 125000,
            'gudang_id' => 2
        ], [
            'kode' => 'TMD2',
            'nama' => 'TRACE MINERAL DROP 2 OZ',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 185000,
            'gudang_id' => 2
        ], [
            'kode' => 'TMD4',
            'nama' => 'TRACE MINERAL DROP 4 OZ',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 310000,
            'gudang_id' => 2
        ], [
            'kode' => 'TMD8',
            'nama' => 'TRACE MINERAL DROP 8 OZ',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 425000,
            'gudang_id' => 2
        ], [
            'kode' => 'ECM30',
            'nama' => 'E-CHARGED MULTI 30 TABS',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 195000,
            'gudang_id' => 2
        ], [
            'kode' => 'TMT30',
            'nama' => 'TRACE MINERAL TABLETS 30 TABS',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 185000,
            'gudang_id' => 2
        ], [
            'kode' => 'ARX30',
            'nama' => 'ARTH-X BONE 30 TABS',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 250000,
            'gudang_id' => 2
        ], [
            'kode' => 'DMB30',
            'nama' => 'FEED MYBRAIN 30 TABS',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 165000,
            'gudang_id' => 2
        ], [
            'kode' => 'FMB60',
            'nama' => 'FEED MYBRAIN 60 TABS',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 310000,
            'gudang_id' => 2
        ], [
            'kode' => 'CCC90',
            'nama' => 'COLON CARE CAPS 90 TABS',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 240000,
            'gudang_id' => 2
        ], [
            'kode' => 'CCC30',
            'nama' => 'RENEWAL CAPS 30 TABS',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 275000,
            'gudang_id' => 2
        ], [
            'kode' => 'TMS02',
            'nama' => 'TRACE MINERAL ALOEVERA SOAP',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 45000,
            'gudang_id' => 2
        ], [
            'kode' => 'FDC01',
            'nama' => 'FRESH DEODORANT CRYSTAL',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 26000,
            'gudang_id' => 2
        ], [
            'kode' => 'CWS01',
            'nama' => 'COLLAGEN SOAP',
            'stok_awal' => 0,
            'stok_akhir' => 0,
            'harga' => 70000,
            'gudang_id' => 2
        ]];

        foreach ($data as $d)
            Barang::create($d);
    }
}
