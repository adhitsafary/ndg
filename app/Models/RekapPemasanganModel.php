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
        'marketing',
        'sn_modem',
        'maps',
        'total_biaya',
        'inventory_keluar',
        'teknisi',
        'id_plg',
        'odp',
        'admin',
        'kt_plg',

    ];

    public function modem()
    {
        return $this->hasOne(Modem::class, 'sn_modem', 'sn_modem');
    }

    protected $casts = [
        'teknisi' => 'array',
        'inventory_keluar' => 'array',
        'total_biaya' => 'decimal:2',
    ];



    protected static function boot()
    {
        parent::boot();

        static::created(function ($rekap_pemasangan) {
            if ($rekap_pemasangan->inventory()->exists()) {
                foreach ($rekap_pemasangan->inventory as $inv_keluar) {
                    $inventory = Inventory::where('nm_brg', $inv_keluar->nm_brg)->first();
                    if ($inventory) {
                        $inventory->jml_brg -= $inv_keluar->jml_brg;
                        $inventory->save();
                    }
                }
            }
        });
    }


    public function inventory()
    {
        return $this->hasMany(InventoryKeluar::class, 'rekap_pemasangan_id');
    }
}
