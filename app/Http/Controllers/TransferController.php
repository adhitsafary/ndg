<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('transfer.index', compact('users'));
    }



    public function transferSaldo(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'jumlah_saldo' => 'required|numeric|min:100',
        ]);

        $pengirim = Auth::user();
        $penerima = User::find($request->receiver_id);

        if ($pengirim->saldo < $request->jumlah_saldo) {
            return back()->with('error', 'Saldo tidak mencukupi!');
        }

        // Proses transfer
        $pengirim->saldo -= $request->jumlah_saldo;
        $pengirim->save();

        $penerima->saldo += $request->jumlah_saldo;
        $penerima->save();

        return redirect()->route('transfer.index')->with('success', 'Transfer berhasil!');
    }
}
