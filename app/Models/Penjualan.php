<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'id';
    protected $appends = ['barang'];

    protected $fillable = [
        'tanggal',
        'terjual',
    ];

    public function getBarangAttribute()
    {
        return Barang::find($this->barang_id);
    }
}
