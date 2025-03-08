<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;
    protected $table = 'perbaikan';

    protected $fillable = [
        'id_plg',
        'nama_plg',
        'alamat_plg',
        'no_telepon_plg',
        'paket_plg',
        'odp',
        'maps',
        'keterangan',
        'teknisi',
        'status',
        'kd_tiket',
        'nomor_tiket',
        'info',
       // 'inventory_id',        // Barang yang digunakan dari inventory

    ];

    // Relasi ke model Inventory
   // public function inventory()
   // {
   //     return $this->belongsTo(Inventory::class, 'inventory_id');
  //  }
}



