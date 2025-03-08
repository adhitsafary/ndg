<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory'; // Sesuai dengan nama tabel

    protected $fillable = [
        'nm_brg',
        'jml_brg',
        'satuan',
        'harga_satuan',
        'kategori',
        'admin',
    ];

    protected $appends = ['harga_total'];

    public function getHargaTotalAttribute()
    {
        return $this->jml_brg * $this->harga_satuan;
    }
}
