<?php

namespace App\Http\Controllers;

use App\Models\BuktiByrPlg;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class BuktiPembayaranController extends Controller
{
    public function form($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.form_pembayaran', compact('pelanggan'));
    }


}
