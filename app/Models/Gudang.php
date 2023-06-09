<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;

    protected $table = 'gudang';
    protected $primaryKey = 'id';
    protected $appends = ['alamat', 'vendor'];

    protected $fillable = [
        'kode',
        'nama',
    ];

    public function getAlamatAttribute()
    {
        return Alamat::where('gudang_id', '=', $this->id)->get();
    }

    public function getVendorAttribute()
    {
        return Vendor::find($this->vendor_id);
    }
}
