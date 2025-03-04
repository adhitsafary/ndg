<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranModel extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran'; // Pastikan ini benar
    protected $primaryKey = 'id'; // Pastikan ini benar jika menggunakan AUTO_INCREMENT
    public $timestamps = true; // Jika menggunakan `created_at` dan `updated_at`

    protected $fillable = [
        'keterangan',
        'deskripsi',
        'harga_satuan',
        'volume',
        'harga_total',
        'kategori'
    ];
}
