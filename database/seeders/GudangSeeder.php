<?php

namespace Database\Seeders;

use App\Models\Gudang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gudang::create([
            'id' => 1,
            'kode' => 'GU',
            'nama' => 'Gudang Utama',
            'vendor_id' => 1
        ]);

        Gudang::create([
            'id' => 2,
            'kode' => 'GSJ',
            'nama' => 'Gudang Siap Jual',
            'vendor_id' => 1
        ]);
    }
}
