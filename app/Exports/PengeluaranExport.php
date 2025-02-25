<?php

namespace App\Exports;


use App\Models\PengeluaranModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengeluaranExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PengeluaranModel::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get(['deskripsi', 'harga_total', 'created_at']);
    }

    public function headings(): array
    {
        return ["deskripsi", "harga_total", "Tanggal"];
    }
}
