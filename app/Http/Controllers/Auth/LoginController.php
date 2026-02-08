<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Tampilkan form login + generate captcha
     */
    public function showLoginForm()
    {
        $this->generateCaptcha();
        return view('auth.login');
    }

    /**
     * Generate captcha dan simpan ke session
     */
    protected function generateCaptcha()
    {
        $captcha = strtoupper(Str::random(6));
        session(['captcha' => $captcha]);
    }

    /**
     * Proses login + validasi captcha
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'captcha' => ['required'],
        ], [
            'captcha.required' => 'Captcha wajib diisi.',
        ]);

        // Validasi captcha
        if ($request->captcha !== session('captcha')) {
            $this->generateCaptcha(); // refresh captcha

            return back()
                ->withErrors(['captcha' => 'Captcha tidak sesuai.'])
                ->withInput($request->except('password'));
        }

        // Validasi login
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            session()->forget('captcha');
            return redirect()->intended('/dashboard');
        }

        // Login gagal
        $this->generateCaptcha();

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->except('password'));
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
