<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\DatasetImport;
use App\Models\Dataset;
use App\Models\DatasetRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DatasetUploadController extends Controller
{
    /**
     * Upload dataset, baca isinya, lalu simpan ke MySQL.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Pastikan pengguna terhubung dengan divisi.
        if (!$user->department_id) {
            return response()->json([
                'message' => 'Akun belum terhubung dengan divisi.',
            ], 403);
        }

        // Validasi data yang dikirim.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'file' => [
                'required',
                'file',
                'mimes:csv,xlsx',
                'max:10240',
            ],
        ]);

        $file = $request->file('file');

        // Simpan file pada penyimpanan lokal privat.
        $path = $file->store('datasets', 'local');

        if (!$path) {
            return response()->json([
                'message' => 'File gagal disimpan.',
            ], 500);
        }

        try {
            // Baca isi file menggunakan Laravel Excel.
            $sheets = Excel::toArray(
                new DatasetImport(),
                $file
            );

            // Untuk tahap ini, gunakan sheet pertama.
            $rows = $sheets[0] ?? [];

            if (count($rows) < 2) {
                throw new \RuntimeException(
                    'File harus memiliki baris header dan minimal satu baris data.'
                );
            }

            // Baris pertama dianggap sebagai nama kolom.
            $headers = array_map(
                fn ($header) => trim((string) $header),
                $rows[0]
            );

            // Periksa header agar tidak kosong seluruhnya.
            if (
                count(array_filter(
                    $headers,
                    fn ($header) => $header !== ''
                )) === 0
            ) {
                throw new \RuntimeException(
                    'Baris pertama file harus berisi nama kolom.'
                );
            }

            // Isi header kosong dan hindari nama kolom duplikat.
            $uniqueHeaders = [];
            $usedHeaders = [];

            foreach ($headers as $index => $header) {
                if ($header === '') {
                    $header = 'column_' . ($index + 1);
                }

                $baseHeader = $header;
                $suffix = 2;

                while (in_array($header, $usedHeaders, true)) {
                    $header = $baseHeader . '_' . $suffix;
                    $suffix++;
                }

                $usedHeaders[] = $header;
                $uniqueHeaders[] = $header;
            }

            // Ubah baris file menjadi objek data berdasarkan header.
            $records = [];

            foreach (array_slice($rows, 1) as $row) {
                $row = array_values($row);

                // Abaikan baris yang seluruh kolomnya kosong.
                if (count(array_filter(
                    $row,
                    fn ($value) => $value !== null && $value !== ''
                )) === 0) {
                    continue;
                }

                // Samakan jumlah kolom dengan jumlah header.
                $row = array_slice(
                    array_pad($row, count($uniqueHeaders), null),
                    0,
                    count($uniqueHeaders)
                );

                $recordData = array_combine(
                    $uniqueHeaders,
                    $row
                );

                $records[] = $recordData;
            }

            if (count($records) === 0) {
                throw new \RuntimeException(
                    'Tidak ada baris data yang dapat diimpor.'
                );
            }

            // Simpan dataset dan seluruh record dalam satu transaksi.
            $dataset = DB::transaction(function () use (
                $user,
                $validated,
                $file,
                $path,
                $records
            ) {
                $dataset = Dataset::create([
                    'department_id' => $user->department_id,
                    'uploaded_by' => $user->id,
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? null,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => strtolower(
                        $file->getClientOriginalExtension()
                    ),
                    'status' => 'uploaded',
                ]);

                foreach ($records as $recordData) {
                    DatasetRecord::create([
                        'dataset_id' => $dataset->id,
                        'record_data' => $recordData,
                    ]);
                }

                return $dataset;
            });

            return response()->json([
                'message' => 'Dataset berhasil diunggah dan diimpor.',
                'data' => [
                    'id' => $dataset->id,
                    'name' => $dataset->name,
                    'description' => $dataset->description,
                    'original_filename' => $dataset->original_filename,
                    'file_type' => $dataset->file_type,
                    'status' => $dataset->status,
                    'department_id' => $dataset->department_id,
                    'uploaded_by' => $dataset->uploaded_by,
                    'total_records' => count($records),
                    'created_at' => $dataset->created_at,
                ],
            ], 201);

        } catch (\Throwable $e) {
            // Hapus file jika proses impor atau penyimpanan gagal.
            Storage::disk('local')->delete($path);

            report($e);

            if ($e instanceof \RuntimeException) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 422);
            }

            return response()->json([
                'message' => 'Upload atau impor dataset gagal. Periksa format file dan log aplikasi.',
            ], 500);
        }
    }
}