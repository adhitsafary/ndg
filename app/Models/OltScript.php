<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OltScript extends Model
{
    protected $fillable = [
        'sn',
        'port',
        'slot',
        'pon',
        'number_onu',
        'type_modem',
        'nama_modem',
        'tcont_profile',
        'vlan',
        'mode',
        'pppoe_user',
        'pppoe_pass',
        'generated_script'
    ];
}
