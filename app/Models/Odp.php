<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odp extends Model
{
    use HasFactory;

    protected $table = 'odp';

    protected $fillable = [
        'nama_odp',
        'jml_port',
        'longitude',
        'latitude',
    ];

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'odp', 'nama_odp');
    }
}
