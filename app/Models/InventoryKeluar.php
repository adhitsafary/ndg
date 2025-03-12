<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryKeluar extends Model
{
    use HasFactory;

    protected $table = 'inventory_keluar';

    protected $fillable = [
        'perbaikan_id',  // Tambahkan jika tidak ada
        'nm_brg',
        'jml_brg',
        'harga_satuan',
        'rekap_pemasangan_id',
    ];

    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'nm_brg', 'nm_brg');
    }

    public function rekap_pemasangan()
    {
        return $this->belongsTo(RekapPemasanganModel::class, 'rekap_pemasangan_id');
    }
}
