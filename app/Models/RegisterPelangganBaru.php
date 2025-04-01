<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterPelangganBaru extends Model
{
    use HasFactory;
    protected $table = 'register_pelanggan_baru';
    protected $fillable = [
        'nama_plg',
        'nik_plg',
        'no_tlp_plg',
        'email_plg',
        'alamat_plg',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'paket_plg'
    ];
}
