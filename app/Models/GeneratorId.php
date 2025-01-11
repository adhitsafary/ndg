<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratorId extends Model
{
    use HasFactory;

    protected $table = 'generator_id';

    protected $fillable = [
        'kode_perusahaan',
        'kode_paket_plg',
        'kode_nik',
        'kode_odp',
    ];

    public function getFullIdAttribute()
    {
        return $this->kode_perusahaan . $this->kode_paket_plg . $this->kode_nik . $this->kode_odp;
    }
}
