<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BayarPelanggan;

//// buatkan login page pelanggan masuk dengan id_plg, buatkan halaman dan conrtollernya
class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'pelanggan';

    protected $fillable = [

        'id_plg',
        'nama_plg',
        'alamat_plg',
        'no_telepon_plg',
        'aktivasi_plg',
        'paket_plg',
        'harga_paket',
        'tgl_tagih_plg',
        'keterangan_plg',
        'odp',
        'latitude',
        'longitude',
        'status_pembayaran',
        'kode_unik',
        'nik',
        'maps',

    ];


    // Relasi ke tabel bayar_pelanggan
    public function pembayaran()
    {
        return $this->hasMany(BayarPelanggan::class, 'pelanggan_id', 'id'); // Menghubungkan ke foreign key 'pelanggan_id'
    }

    // Relasi ke tabel bayar_pelanggan untuk mengambil pembayaran terakhir
    public function pembayaranTerakhir()
    {
        return $this->hasOne(BayarPelanggan::class, 'pelanggan_id', 'id') // Menghubungkan ke foreign key 'pelanggan_id'
        ->latest('tanggal_pembayaran'); // Mengambil pembayaran terakhir berdasarkan 'created_at'
           // ->latest('created_at'); // Mengambil pembayaran terakhir berdasarkan 'created_at'
    }







}
