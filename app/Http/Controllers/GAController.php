<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\Pelanggan;
use App\Models\Perbaikan;
use App\Models\RekapPemasanganModel;
use App\Models\User;
use App\Models\X100c;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GAController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hariIni = Carbon::now()->toDateString(); // Ambil tanggal hari ini dalam format YYYY-MM-DD

        // Ambil semua user
        $user = User::whereDate('created_at', $hariIni)->get();

        // Filter data absensi berdasarkan tanggal hari ini
        $absensi = X100c::whereDate('created_at', $hariIni)->get();

        // Filter pembayaran berdasarkan tanggal hari ini
        $pembayaran = BayarPelanggan::whereDate('created_at', $hariIni)->get();

        // Filter perbaikan yang berstatus 'Success' berdasarkan tanggal hari ini
        $perbaikan = Perbaikan::where('status', 'Success')
            ->whereDate('created_at', $hariIni)
            ->get();

        // Filter pemasangan (PSB) yang berstatus 'open' berdasarkan tanggal hari ini
        $psb = RekapPemasanganModel::where('status', 'open')
            ->whereDate('created_at', $hariIni)
            ->get();

        $pelanggan = Pelanggan::whereDate('tgl_tagih_plg', $hariIni)
            ->orderBy('tgl_tagih_plg', 'asc')
            ->get();

        return view('GA.index', compact('user', 'absensi', 'pembayaran', 'perbaikan', 'psb', 'pelanggan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
