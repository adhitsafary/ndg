<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Tampilkan daftar inventory.
     */
    public function index()
    {
        $inventories = Inventory::all();
        return view('inventory.index', compact('inventories'));
    }

    /**
     * Tampilkan form tambah data.
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * Simpan data baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nm_brg' => 'required|string|max:255',
            'jml_brg' => 'required|integer|min:1',
            'satuan' => 'required|in:meter,pcs',
            'harga_satuan' => 'required|numeric|min:0',
            'kategori' => 'required|string|max:255',

        ]);

        $admin = Auth::user() ? Auth::user()->name : 'Unknown Admin';

        // Cek apakah barang sudah ada
        $inventory = Inventory::where('nm_brg', $request->nm_brg)->first();

        if ($inventory) {
            // Jika sudah ada, update jumlah dan harga total
            $inventory->update([
                'jml_brg' => $inventory->jml_brg + $request->jml_brg,
                'harga_total' => ($inventory->jml_brg + $request->jml_brg) * $inventory->harga_satuan,
            ]);
        } else {
            // Jika belum ada, buat baru
            Inventory::create([
                'nm_brg' => $request->nm_brg,
                'jml_brg' => $request->jml_brg,
                'satuan' => $request->satuan,
                'harga_satuan' => $request->harga_satuan,
                'harga_total' => $request->jml_brg * $request->harga_satuan,
                'kategori' => $request->kategori,
                'admin' => $admin,
            ]);
        }


        if ($inventory) {
            return redirect()->route('inventory.index') //)//)//, $inventory->id)
                ->with('success', 'Barang berhasil dimasukan ke database inventory ' . $inventory->nm_brg . '.');
        } else {
            return redirect()->route('inventory.index')// ,$inventory->id)
                ->with('error', 'Barang gagal dimasukan ke database inventory ' . $inventory->nm_brg . '. Silakan coba lagi!');
        }
    }

    /**
     * Tampilkan form edit data.
     */
    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    /**
     * Update data di database.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'nm_brg' => 'required|string|max:255',
            'jml_brg' => 'required|integer|min:1',
            'satuan' => 'required|in:meter,pcs',
            'harga_satuan' => 'required|numeric|min:0',
            'kategori' => 'required|string|max:255',
        ]);

        $admin = Auth::user() ? Auth::user()->name : 'Unknown Admin';

        // Hitung harga total
        $harga_total = $request->jml_brg * $request->harga_satuan;

        $inventory->update([
            'nm_brg' => $request->nm_brg,
            'jml_brg' => $request->jml_brg,
            'satuan' => $request->satuan,
            'harga_satuan' => $request->harga_satuan,
            'harga_total' => $harga_total,
            'kategori' => $request->kategori,
            'admin' => $admin,

        ]);


        if ($inventory) {
            return redirect()->route('inventory.index') //, $inventory->id)
                ->with('success', 'Barang berhasil Update ke database inventory ' . $inventory->nm_brg . '.');
        } else {
            return redirect()->route('inventory.index') //, $inventory->id)
                ->with('error', 'Barang gagal di Update ke database inventory ' . $inventory->nm_brg . '. Silakan coba lagi!');
        }
    }

    /**
     * Hapus data dari database.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        if ($inventory) {
            return redirect()->route('inventory.index') //, $inventory->id)
                ->with('success', 'Barang berhasil di Hapus di database inventory ' . $inventory->nm_brg . '.');
        } else {
            return redirect()->route('inventory.index') //, $inventory->id)
                ->with('error', 'Barang gagal di Hapus di database inventory ' . $inventory->nm_brg . '. Silakan coba lagi!');
        }
    }
}
