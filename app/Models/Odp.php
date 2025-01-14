<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odp extends Model
{
    use HasFactory;

    protected $table = 'odp';

    protected $fillable = [
        'kecamatan',
        'desa',
        'dusun',
        'jml_odp',
        'kode_odp',
        'jml_port',
        'longitude',
        'latitude',
        'no_urut_odp',


    ];

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'odp', 'kode_odp');
    }
}
