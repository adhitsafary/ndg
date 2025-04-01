<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PesanController extends Controller
{
    public function index()
    {
        $pesan = Pesan::all();
        return view('pesan.index', compact('pesan'));
    }

    public function create()
    {
        return view('pesan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'admin' => 'required',
            'penerima' => 'required',
            'pesan' => 'required',
        ]);

        Pesan::create($request->all());
        return redirect()->route('pesan.index')->with('success', 'Pesan berhasil dikirim');
    }

    public function edit(Pesan $pesan)
    {
        return view('pesan.edit', compact('pesan'));
    }

    public function update(Request $request, Pesan $pesan)
    {
        $request->validate([
            'admin' => 'required',
            'penerima' => 'required',
            'pesan' => 'required',
        ]);

        $pesan->update($request->all());
        return redirect()->route('pesan.index')->with('success', 'Pesan berhasil diperbarui');
    }

    public function destroy(Pesan $pesan)
    {
        $pesan->delete();
        return redirect()->route('pesan.index')->with('success', 'Pesan berhasil dihapus');
    }



    private function sendTelegramNotification($telegram_bot)
    {
        $token = '7085351448:AAErPRbIkJJOwkDTIMFUlwNU3AN_UQ1cRkY';
        $chat_id = '5985430823';
        $url = "https://api.telegram.org/bot{$token}/sendMessage";



        // Menyiapkan pesan untuk Telegram
        $message =
            "💰 *Notifikasi Pembayaran Baru*\n" .
            "========================\n" .
            "👤 *Pelanggan :* {$telegram_bot->adminName}\n" .
            "📝 *Alamat :* {$telegram_bot->pesan}\n" .
            "=========================\n" .


            $client = new Client();

        try {
            $client->post($url, [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("Telegram Notification Error: " . $e->getMessage());
        }
    }
}
