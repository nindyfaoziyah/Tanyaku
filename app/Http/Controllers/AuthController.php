<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function checkEmail(Request $request): JsonResponse
    {
        $email = $request->query('email');

        if (! is_string($email) || ! Validator::make(['email' => $email], [
            'email' => ['required', 'email'],
        ])->passes()) {
            return response()->json([
                'valid' => false,
                'registered' => false,
            ]);
        }

        return response()->json([
            'valid' => true,
            'registered' => User::query()->where('email', $email)->exists(),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email perusahaan wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (! User::query()->where('email', $credentials['email'])->exists()) {
            return back()
                ->withErrors(['email' => 'Email belum terdaftar. Periksa kembali.'])
                ->withInput($request->only('email'));
        }

        $credentials['is_active'] = true;

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()
            ->withErrors(['password' => 'Kata sandi yang kamu masukkan salah.'])
            ->withInput($request->only('email'))
            ->with('login_failure', true);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Kamu berhasil logout.');
    }
}
