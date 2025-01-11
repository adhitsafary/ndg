<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapPemasanganModel extends Model
{
    use HasFactory;
    protected $table = 'rekap_pemasangan';

    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'no_telpon',
        'tgl_aktivasi',
        'paket_plg',
        'nominal',
        'jt',
        'status',
        'tgl_pengajuan',
        'registrasi',
        'marketing'
    ];

    public function modem()
    {
        return $this->hasOne(Modem::class, 'sn_modem', 'sn_modem');
    }
}
