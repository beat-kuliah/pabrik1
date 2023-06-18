<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendor';
    protected $primaryKey = 'id';
    protected $appends = ['alamat'];

    protected $fillable = [
        'kode',
        'nama',
    ];

    public function getAlamatAttribute()
    {
        return Alamat::where('vendor_id', '==', $this->id)->get();
    }
}
