<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran'; // Sesuaikan dengan nama tabel

    protected $fillable = [
        'pelanggan_id',
        'tanggal_pembayaran',
        'jumlah_pembayaran',
        'metode_transaksi',
    ];

    protected $dates = ['tanggal_pembayaran'];

    public function bayarPelanggan()
    {
        return $this->belongsTo(BayarPelanggan::class, 'pelanggan_id', 'pelanggan_id');
    }
}
