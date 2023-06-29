<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id';
    protected $appends = ['gudang'];

    protected $fillable = [
        'kode',
        'nama',
        'stok_awal',
        'stok_akhir',
        'harga',
    ];

    public function getGudangAttribute()
    {
        return Gudang::find($this->gudang_id);
    }
}
