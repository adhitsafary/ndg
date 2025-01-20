<?php

namespace App\Http\Controllers;

use App\Models\BotToken;
use Illuminate\Http\Request;

class BotTokenController extends Controller
{
    public function index()
    {
        $tokens = BotToken::all();
        return view('bot_tokens.index', compact('tokens'));
    }

    public function create()
    {
        return view('bot_tokens.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'token' => 'required|string|max:255',
        ]);

        BotToken::create($request->all());

        return redirect()->route('bot_tokens.index')->with('success', 'Token berhasil disimpan.');
    }

    public function edit(BotToken $botToken)
    {
        return view('bot_tokens.edit', ['token' => $botToken]);
    }

    public function update(Request $request, BotToken $botToken)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'token' => 'required|string|max:255',
        ]);

        $botToken->update($request->all());

        return redirect()->route('bot_tokens.index')->with('success', 'Token berhasil diperbarui.');
    }

    public function destroy(BotToken $botToken)
    {
        $botToken->delete();

        return redirect()->route('bot_tokens.index')->with('success', 'Token berhasil dihapus.');
    }
}
