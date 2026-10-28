<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    /**
     * Mengambil daftar dataset milik divisi pengguna.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Dataset::query()
            ->with(['department', 'uploader']);

        // Pengguna biasa hanya dapat melihat dataset divisinya.
        if (strtoupper($user->role?->name ?? '') !== 'ADMIN') {
            if (!$user->department_id) {
                return response()->json([
                    'message' => 'Pengguna belum terhubung dengan divisi.',
                ], 403);
            }

            $query->where('department_id', $user->department_id);
        }

        $datasets = $query
            ->latest()
            ->paginate(10);

        return response()->json($datasets);
    }

    /**
     * Mengambil detail dataset berdasarkan ID.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $query = Dataset::query()
            ->with(['department', 'uploader', 'records']);

        if (strtoupper($user->role?->name ?? '') !== 'ADMIN') {
            if (!$user->department_id) {
                return response()->json([
                    'message' => 'Pengguna belum terhubung dengan divisi.',
                ], 403);
            }

            $query->where('department_id', $user->department_id);
        }

        $dataset = $query->find($id);

        if (!$dataset) {
            return response()->json([
                'message' => 'Dataset tidak ditemukan atau kamu tidak memiliki akses.',
            ], 404);
        }

        return response()->json([
            'data' => $dataset,
        ]);
    }

    /**
     * Mengambil record dari dataset yang dapat diakses.
     */
    public function records(Request $request, $id)
    {
        $user = $request->user();

        $query = Dataset::query();

        if (strtoupper($user->role?->name ?? '') !== 'ADMIN') {
            if (!$user->department_id) {
                return response()->json([
                    'message' => 'Pengguna belum terhubung dengan divisi.',
                ], 403);
            }

            $query->where('department_id', $user->department_id);
        }

        $dataset = $query->find($id);

        if (!$dataset) {
            return response()->json([
                'message' => 'Dataset tidak ditemukan atau kamu tidak memiliki akses.',
            ], 404);
        }

        return response()->json(
            $dataset->records()->paginate(50)
        );
    }
}
