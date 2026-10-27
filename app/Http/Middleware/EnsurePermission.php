<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if (!$user->is_active) {
            abort(403, 'Akun kamu tidak aktif.');
        }

        $hasPermission = $user->role()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('code', $permission);
            })
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Kamu tidak memiliki izin untuk mengakses fitur ini.');
        }

        return $next($request);
    }
}