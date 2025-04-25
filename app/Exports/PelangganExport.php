<?php

namespace App\Exports;

use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PelangganExport implements FromCollection, WithHeadings
{
    protected $pelanggan;

    // Constructor untuk menerima data pelanggan
    public function __construct($pelanggan)
    {
        $this->pelanggan = $pelanggan;
    }

    /**
     * Return a collection of data to be exported.
     */
    public function collection()
    {
        return $this->pelanggan->values()->map(function ($item, $key) {
            return [
                $key + 1, // ✅ Nomor urut dimulai dari 1
                $item->id_plg,
                $item->nama_plg,
                $item->alamat_plg,
                $item->no_telepon_plg,
                $item->paket_plg,
                $item->harga_paket,
                $item->tgl_tagih_plg,
                $item->status_pembayaran,
            ];
        });
    }


    /**
     * Define the headings for the Excel export.
     */
    public function headings(): array
    {
        return [
            'NO',
            'ID',
            'Nama',
            'Alamat',
            'No Telepon',
            'Paket',
            'Harga',
            'Tanggal Tagih',
            'Status Pembayaran'
        ];
    }
}
