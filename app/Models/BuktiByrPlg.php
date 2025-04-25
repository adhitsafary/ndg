<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiByrPlg extends Model
{
    use HasFactory;

    protected $table = 'bukti_byr_plg';

    protected $fillable = [
        'id_plg',
        'tanggal_pembayaran',
        'jumlah_pembayaran',
        'metode_transaksi',
        'nama_pengirim',
        'bukti_transfer',
        'nama_plg',
        'alamat_plg',
        'no_telepon_plg',
        'harga_paket',
        'tgl_tagih_plg',
        'status_pembayaran',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_plg');
    }
}
