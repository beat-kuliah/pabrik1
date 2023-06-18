<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id';
    protected $appends = ['vendor', 'gudang'];

    protected $fillable = [
        'kode',
        'nama',
        'stok_awal',
        'stok_akhir',
        'harga',
    ];

    public function getVendorAttribute()
    {
        return Vendor::find($this->vendor_id);
    }

    public function getGudangAttribute()
    {
        return Gudang::find($this->gudang_id);
    }
}
