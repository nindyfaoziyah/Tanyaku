<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\DatasetRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Http\UploadedFile;

class DatasetController extends Controller
{
    private function can(string $permission): bool
    {
        $user = auth()->user();

        return $user
            && $user->is_active
            && $user->role()
                ->whereHas('permissions', function ($query) use ($permission) {
                    $query->where('code', $permission);
                })
                ->exists();
    }

    private function visibleDatasets()
    {
        $query = Dataset::query();

        // Admin dapat melihat semua divisi jika memiliki permission.
        // Pengguna divisi hanya dapat melihat dataset divisinya.
        if (auth()->user()->role->name !== 'ADMIN') {
            $query->where(
                'department_id',
                auth()->user()->department_id
            );
        }

        return $query;
    }

    private function writeAuditLog(
        string $action,
        string $resourceType,
        ?int $resourceId,
        string $status
    ): void {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'status' => $status,
            'ip_address' => request()->ip(),
        ]);
    }

    public function index()
    {
        abort_unless($this->can('dataset.view'), 403);

        $datasets = $this->visibleDatasets()
            ->with(['department', 'uploader'])
            ->latest()
            ->paginate(10);

        return view('datasets.index', compact('datasets'));
    }

    public function create()
    {
        abort_unless($this->can('dataset.create'), 403);

        return view('datasets.create');
    }

    public function store(Request $request)
    {
        abort_unless($this->can('dataset.create'), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:2000'],
            'file' => [
                'required',
                'file',
                'max:10240',
                'mimes:csv,xlsx,xls',
            ],
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, ['csv', 'xlsx', 'xls'], true)) {
            return back()
                ->withInput()
                ->withErrors(['file' => 'Format file tidak didukung.']);
        }

        $storedPath = null;
        $dataset = null;

        try {
            $storedPath = $file->storeAs(
                'datasets/' . now()->format('Y/m'),
                Str::uuid() . '.' . $extension,
                'local'
            );

            if (!$storedPath) {
                throw new \RuntimeException('File gagal disimpan.');
            }

            $absolutePath = Storage::disk('local')->path($storedPath);

            $rows = $this->parseDatasetFile($absolutePath, $extension);

            if (count($rows) > 10000) {
                throw new \RuntimeException(
                    'Dataset maksimal berisi 10.000 baris.'
                );
            }

            DB::transaction(function () use (
                $validated,
                $file,
                $extension,
                $storedPath,
                $rows,
                &$dataset
            ) {
                $dataset = Dataset::create([
                    'department_id' => auth()->user()->department_id,
                    'uploaded_by' => auth()->id(),
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? null,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $storedPath,
                    'file_type' => $extension,
                    'status' => 'PROCESSING',
                ]);

                foreach (array_chunk($rows, 500) as $chunk) {
                    $records = [];

                    foreach ($chunk as $row) {
                        $records[] = [
                            'dataset_id' => $dataset->id,
                            'record_data' => json_encode(
                                $row,
                                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
                            ),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    DatasetRecord::insert($records);
                }

                $dataset->update(['status' => 'READY']);

                $this->writeAuditLog(
                    'UPLOAD',
                    'dataset',
                    $dataset->id,
                    'SUCCESS'
                );
            });

            return redirect()
                ->route('datasets.show', $dataset)
                ->with('success', 'Dataset berhasil diunggah dan diproses.');
        } catch (\Throwable $e) {
            if ($storedPath) {
                Storage::disk('local')->delete($storedPath);
            }

            if ($dataset && $dataset->exists) {
                DatasetRecord::where('dataset_id', $dataset->id)->delete();
                $dataset->delete();
            }

            report($e);

            $this->writeAuditLog(
                'UPLOAD',
                'dataset',
                $dataset?->id,
                'FAILED'
            );

            return back()
                ->withInput()
                ->withErrors([
                    'file' => 'Dataset gagal diproses. Periksa format file dan log aplikasi.',
                ]);
        }
    }

    public function show(Dataset $dataset)
    {
        abort_unless($this->can('dataset.view'), 403);

        $this->ensureDatasetVisible($dataset);

        $dataset->load(['department', 'uploader', 'records']);

        return view('datasets.show', compact('dataset'));
    }

    public function edit(Dataset $dataset)
    {
        abort_unless($this->can('dataset.update'), 403);

        $this->ensureDatasetVisible($dataset);

        return view('datasets.edit', compact('dataset'));
    }

    public function update(Request $request, Dataset $dataset)
    {
        abort_unless($this->can('dataset.update'), 403);

        $this->ensureDatasetVisible($dataset);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($dataset, $validated) {
            $dataset->update($validated);

            $this->writeAuditLog(
                'UPDATE',
                'dataset',
                $dataset->id,
                'SUCCESS'
            );
        });

        return redirect()
            ->route('datasets.show', $dataset)
            ->with('success', 'Dataset berhasil diperbarui.');
    }

    public function destroy(Dataset $dataset)
    {
        abort_unless($this->can('dataset.delete'), 403);

        $this->ensureDatasetVisible($dataset);

        DB::transaction(function () use ($dataset) {
            $id = $dataset->id;

            $dataset->delete();

            $this->writeAuditLog(
                'DELETE',
                'dataset',
                $id,
                'SUCCESS'
            );
        });

        return redirect()
            ->route('datasets.index')
            ->with('success', 'Dataset berhasil dihapus.');
    }

    private function ensureDatasetVisible(Dataset $dataset): void
    {
        $user = auth()->user();

        if (
            $user->role->name !== 'ADMIN'
            && $dataset->department_id !== $user->department_id
        ) {
            abort(403, 'Kamu tidak memiliki akses ke dataset ini.');
        }
    }

    private function parseDatasetFile(
        string $path,
        string $extension
    ): array {
        if ($extension === 'csv') {
            $file = fopen($path, 'r');

            if ($file === false) {
                throw new \RuntimeException('File CSV tidak dapat dibaca.');
            }

            try {
                $header = fgetcsv($file);

                if ($header === false || empty($header)) {
                    throw new \RuntimeException('File CSV kosong atau tidak memiliki header.');
                }

                $header = array_map(
                    fn ($value) => trim((string) $value),
                    $header
                );

                if (count(array_filter($header, fn ($value) => $value !== '')) !== count($header)) {
                    throw new \RuntimeException('Semua kolom CSV harus memiliki nama.');
                }

                $rows = [];

                while (($row = fgetcsv($file)) !== false) {
                    if (count(array_filter($row, fn ($value) => $value !== null && $value !== '')) === 0) {
                        continue;
                    }

                    if (count($row) !== count($header)) {
                        throw new \RuntimeException('Jumlah kolom pada baris CSV tidak konsisten.');
                    }

                    $rows[] = array_combine($header, $row);
                }

                return $rows;
            } finally {
                fclose($file);
            }
        }

        $import = new \App\Imports\DatasetImport();

        Excel::import($import, $path);

        $rows = $import->rows;

        if (empty($rows)) {
            throw new \RuntimeException('File Excel kosong atau tidak memiliki data.');
        }

        foreach ($rows as $row) {
            if (!is_array($row)) {
                throw new \RuntimeException('Format baris Excel tidak valid.');
            }
        }

        return $rows;
    }
}