<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login pengguna dan membuat token API.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (
            !$user ||
            !Hash::check($validated['password'], $user->password_hash) ||
            !$user->is_active
        ) {
            throw ValidationException::withMessages([
                'email' => [
                    'Email atau kata sandi salah, atau akun tidak aktif.',
                ],
            ]);
        }

        $token = $user->createToken('tanyaku-api')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'department_id' => $user->department_id,
                'role_id' => $user->role_id,
            ],
        ]);
    }

    /**
     * Logout dan mencabut token API yang sedang digunakan.
     */
    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Mengambil profil pengguna yang sedang login.
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'department_id' => $user->department_id,
                'role_id' => $user->role_id,
                'is_active' => $user->is_active,
            ],
        ]);
    }
}