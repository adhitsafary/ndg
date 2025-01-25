<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class X100c extends Model
{
    use HasFactory;

    protected $table = 'x100c';

    protected $fillable = [
        'pin',
        'waktu',
        'status',
        'nama',
    ];
}
