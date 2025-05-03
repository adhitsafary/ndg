<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchCabangModel extends Model
{
    use HasFactory;
    protected $table = "branch_cabang";
    protected $fillable = [
        'kode_cabang',
        'nama_cabang',
        'nama_pemilik',
        'alamat',
        'tanggal_bergabung',
        'Kepemilikan',
        'persentase',
    ];
}
