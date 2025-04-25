<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'foto_profile';
            $file->move(public_path($path), $filename);

            // Hapus foto lama kalau ada
            if ($user->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }

            $user->foto = $path . '/' . $filename;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
