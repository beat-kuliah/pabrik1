<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;

    protected $table = 'retur';
    protected $primaryKey = 'id';

    protected $appends = ['penjualan'];

    protected $fillable = [
        'oldId',
        'total_retur'
    ];

    public function getPenjualanAttribute()
    {
        return Penjualan::find($this->oldId);
    }
}
