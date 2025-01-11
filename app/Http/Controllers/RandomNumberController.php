<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RandomNumber;

class RandomNumberController extends Controller
{
    public function index()
    {
        $results = RandomNumber::latest()->get();

        foreach ($results as $result) {
            $result->numbers = json_decode($result->numbers, true);
        }

        return view('random_numbers.index', compact('results'));
    }

    /**
     * Menghasilkan angka acak berdasarkan input.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'range' => 'required|integer|min:1', // Prefix berasal dari range
            'suffix' => 'nullable|string', // Opsional, bisa untuk domain atau string tambahan
        ]);

        $quantity = $request->quantity; // Jumlah angka yang akan dihasilkan
        $prefix = str_pad((string)$request->range, 5, '0', STR_PAD_LEFT); // Prefix dari input range
        $suffix = $request->suffix ?? ''; // Tambahan string opsional, seperti domain

        // Generate angka sesuai format yang diminta
        $numbers = [];
        for ($i = 1; $i <= $quantity; $i++) {
            $increment = str_pad((string)$i, 7, '0', STR_PAD_LEFT); // Tambahkan angka ke-1, ke-2, dst.
            $numbers[] = $prefix . $increment . $suffix; // Gabungkan prefix, increment, dan suffix
        }

        RandomNumber::create([
            'quantity' => $quantity,
            'range' => $request->range,
            'numbers' => json_encode($numbers), // Simpan dalam format JSON
            'suffix' => $suffix,
        ]);

        return redirect()->route('random_numbers.index')->with('success', 'Angka acak berhasil dibuat!');
    }


    /**
     * Menghapus data angka acak berdasarkan ID.
     */
    public function delete($id)
    {
        $result = RandomNumber::findOrFail($id);
        $result->delete();

        return redirect()->route('random_numbers.index')->with('success', 'Data berhasil dihapus!');
    }


    public function update(Request $request, $id)
    {
        // Validasi input dari pengguna
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'range' => 'required|integer|min:1',  // Pastikan range valid
            'suffix' => 'nullable|string',
        ]);

        // Ambil data yang akan diupdate
        $result = RandomNumber::findOrFail($id);

        // Decode existing numbers dari format JSON
        $existingNumbers = json_decode($result->numbers, true);
        $newQuantity = $request->quantity;
        $currentCount = count($existingNumbers);

        // Ambil prefix berdasarkan range baru
        $prefix = str_pad((string)$request->range, 5, '0', STR_PAD_LEFT);
        $suffix = $request->suffix ?? $result->suffix; // Suffix baru jika ada perubahan

        // Ambil angka terakhir dari numbers yang ada
        $lastNumber = end($existingNumbers); // Ambil angka terakhir
        $lastIncrement = substr($lastNumber, -7); // Mengambil 7 digit terakhir
        $lastIncrementInt = (int)$lastIncrement; // Ubah ke integer untuk increment

        // Jika jumlah angka baru lebih banyak dari yang sudah ada, lanjutkan urutan angka
        if ($newQuantity > $currentCount) {
            for ($i = $currentCount; $i < $newQuantity; $i++) {
                $lastIncrementInt++; // Increment angka terakhir
                $increment = str_pad((string)$lastIncrementInt, 7, '0', STR_PAD_LEFT); // Format angka dengan 7 digit
                $existingNumbers[] = $prefix . $increment . $suffix; // Gabungkan prefix, increment, dan suffix
            }
        } elseif ($newQuantity < $currentCount) {
            // Jika jumlah angka berkurang, potong array numbers sesuai quantity yang baru
            $existingNumbers = array_slice($existingNumbers, 0, $newQuantity);
        }

        // Update data
        $result->quantity = $newQuantity;
        $result->range = $request->range; // Simpan range baru
        $result->numbers = json_encode($existingNumbers); // Simpan dalam format JSON
        $result->suffix = $suffix; // Update suffix jika ada perubahan
        $result->save();

        return redirect()->route('random_numbers.index')->with('success', 'Data berhasil diperbarui!');
    }

    





}
