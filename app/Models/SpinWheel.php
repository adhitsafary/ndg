<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinWheel extends Model
{
    use HasFactory;

    protected $table = 'spin_wheel'; // Gunakan nama tabel yang benar
    protected $fillable = ['name', 'chance'];
}
