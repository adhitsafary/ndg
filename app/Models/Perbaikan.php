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
        'inventory_keluar',
        'total_biaya',
        'nomor_tiket',
        'kd_tiket',
        'status',
        'info',
        'admin',
    ];

    protected $casts = [
        'teknisi' => 'array',
        'inventory_keluar' => 'array',
        'total_biaya' => 'decimal:2',
    ];


    protected static function boot()
    {
        parent::boot();

        static::created(function ($perbaikan) {
            if (!empty($perbaikan->inventory)) {
                foreach ($perbaikan->inventory as $item) {
                    $inventory = Inventory::where('nm_brg', $item['nm_brg'])->first();
                    if ($inventory) {
                        $inventory->jml_brg -= $item['jml_brg'];
                        $inventory->save();
                    }
                }
            }
        });
    }

    public function inventory()
    {
        return $this->hasMany(InventoryKeluar::class);
    }
}
