<?php

namespace App\Exports;

use App\Models\Modem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModemExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Modem::select('sn_modem', 'model', 'tgl_keluar', 'user', 'id_mikrotik', 'keterangan')->get();
    }

    public function headings(): array
    {
        return ['SN Modem', 'Model', 'Tanggal Keluar', 'User', 'ID MikroTik', 'Keterangan'];
    }
}
