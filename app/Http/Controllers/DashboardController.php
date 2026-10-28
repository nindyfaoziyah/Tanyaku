<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\DatasetRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Periksa apakah pengguna memiliki akses ke seluruh divisi.
        $isAdmin = strtoupper($user->role?->name ?? '') === 'ADMIN';

        // Ambil dataset sesuai hak akses pengguna.
        $datasetQuery = Dataset::query();

        if (!$isAdmin) {
            if (!$user->department_id) {
                $datasetQuery->whereRaw('1 = 0');
            } else {
                $datasetQuery->where(
                    'department_id',
                    $user->department_id
                );
            }
        }

        // Total dataset yang dapat dilihat pengguna.
        $totalDatasets = (clone $datasetQuery)->count();

        // Total record dari dataset yang dapat dilihat pengguna.
        $recordQuery = DatasetRecord::query()
            ->whereHas('dataset', function ($query) use ($user, $isAdmin) {
                if (!$isAdmin) {
                    if (!$user->department_id) {
                        $query->whereRaw('1 = 0');
                    } else {
                        $query->where(
                            'department_id',
                            $user->department_id
                        );
                    }
                }
            });

        $totalRecords = $recordQuery->count();

        // Hitung dataset yang berhasil diproses.
        $readyDatasets = (clone $datasetQuery)
            ->where('status', 'READY')
            ->count();

        // Ambil lima dataset terbaru.
        $recentDatasets = (clone $datasetQuery)
            ->with(['department', 'uploader'])
            ->latest()
            ->take(5)
            ->get();

        // Ambil nama divisi pengguna.
        $departmentName = $user->department?->name ?? 'Belum ditentukan';

        return view('dashboard', compact(
            'user',
            'departmentName',
            'totalDatasets',
            'totalRecords',
            'readyDatasets',
            'recentDatasets',
            'isAdmin'
        ));
    }
}