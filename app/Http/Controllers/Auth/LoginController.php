<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // tampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // proses login
    public function login(Request $request)
    {
        // validasi input
        $request->validate([
            'email' => 'required|email',
            'kata_sandi' => 'required',
        ], [
            'email.required' => 'email wajib diisi',
            'email.email' => 'format email tidak valid',
            'kata_sandi.required' => 'password wajib diisi',
        ]);

        // coba login dengan custom field
        $credentials = [
            'email' => $request->email,
            'password' => $request->kata_sandi,
        ];

        $remember = $request->filled('ingat_saya');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'email atau password salah',
        ])->onlyInput('email');
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
