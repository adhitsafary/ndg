<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\BuktiByrPlg;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Session;

class PelangganLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'id_plg' => 'required|string',
        ]);

        $pelanggan = Pelanggan::where('id_plg', $request->id_plg)->first();

        if ($pelanggan) {
            Session::put('pelanggan_id', $pelanggan->id_plg);
            return redirect()->route('dashboard.pelanggan');
        }

        return back()->withErrors(['id_plg' => 'ID Pelanggan tidak ditemukan.']);
    }

    public function dashboard()
    {
        if (!Session::has('pelanggan_id')) {
            return redirect()->route('login.pelanggan');
        }

        $pelanggan = Pelanggan::where('id_plg', Session::get('pelanggan_id'))->first();
        $riwayatBayar = BayarPelanggan::where('id_plg', $pelanggan->id_plg)->orderBy('tanggal_pembayaran', 'desc')->get();

        return view('user.dashboard', compact('pelanggan', 'riwayatBayar'));
    }


    public function logout()
    {
        Session::forget('pelanggan_id');
        return redirect()->route('login.pelanggan');
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'tanggal_pembayaran' => 'required|date',
            'jumlah_pembayaran' => 'required|integer',
            'metode_transaksi' => 'required',
            'nama_pengirim' => 'nullable|string',
            'bukti_transfer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file bukti transfer
        ]);

        // Format tanggal jika input hanya tahun-bulan (ex: "2025-03")
        $tanggal = $request->tanggal_pembayaran;
        if (strlen($tanggal) === 7) {
            $tanggal .= '-01';
        }

        // Ambil data pelanggan langsung dari database (lebih aman)
        $pelanggan = Pelanggan::where('id_plg', $id)->firstOrFail();

        $filePath = null;
        if ($request->hasFile('bukti_transfer')) {
            // Simpan bukti transfer ke folder public/asset/img/bukti_transfer/
            $file = $request->file('bukti_transfer');
            $filename = time() . '_' . $file->getClientOriginalName(); // Nama file unik
            $path = 'asset/img/bukti_transfer'; // Folder tujuan
            $file->move(public_path($path), $filename); // Simpan file ke folder public

            $filePath = $path . '/' . $filename; // Simpan path file ke database
        }

        // Simpan bukti pembayaran
        BuktiByrPlg::create([
            'id_plg' => $pelanggan->id_plg,
            'tanggal_pembayaran' => $tanggal,
            'jumlah_pembayaran' => $request->jumlah_pembayaran,
            'metode_transaksi' => $request->metode_transaksi,
            'nama_pengirim' => $request->nama_pengirim,
            'bukti_transfer' => $filePath, // Simpan path bukti transfer
            'nama_plg' => $pelanggan->nama_plg,
            'alamat_plg' => $pelanggan->alamat_plg,
            'no_telepon_plg' => $pelanggan->no_telepon_plg,
            'harga_paket' => $pelanggan->harga_paket,
            'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil dikirim!');
    }




    public function lihatBukti($id)
    {
        $pelanggan = Pelanggan::where('id_plg', $id)->firstOrFail();
        $buktiPembayaran = BuktiByrPlg::where('id_plg', $id)->orderBy('created_at', 'desc')->get();

        return view('user.bukti_pembayaran', compact('pelanggan', 'buktiPembayaran'));
    }
}
