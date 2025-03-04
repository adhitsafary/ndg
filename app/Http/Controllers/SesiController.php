<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesiController extends Controller
{
    public function index()
    {
        return view('login.login'); // Ganti dengan view login Anda
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => 'Email Wajib diisi',
                'password.required' => 'Password wajib diisi',
            ]
        );

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Pengalihan berdasarkan role
            return $this->redirectUser();
        } else {
            return redirect()->back()
                ->withErrors('Username dan Password yang dimasukkan tidak sesuai')
                ->withInput();
        }
    }

    private function redirectUser()
    {
        $role = Auth::user()->role;

        // Redirect sesuai dengan role
        switch ($role) {
            case 'teknisi':
                return redirect()->route('teknisi.index'); // Sudah sesuai dengan rute yang ada
            case 'admin':
                return redirect('/masuk/admin'); // Gunakan path, karena rute ini tidak memiliki name()
            case 'finance':
                return redirect('/masuk/finance'); // Tambahkan role finance
            case 'superadmin':
                return redirect('/masuk/superadmin'); // Gunakan path yang sesuai
            default:
                return redirect()->route('login'); // Redirect ke halaman login jika role tidak dikenal
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
