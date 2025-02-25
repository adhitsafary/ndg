<?php

namespace App\Exports;

use App\Models\PemasukanModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PemasukanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PemasukanModel::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get(['keterangan', 'jumlah', 'created_at']);
    }

    public function headings(): array
    {
        return ["Keterangan", "Jumlah", "Tanggal"];
    }
}
