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

    $dataset->load(['department', 'uploader']);

    $records = DatasetRecord::where('dataset_id', $dataset->id)
        ->latest('id')
        ->paginate(20);

    return view('datasets.show', compact('dataset', 'records'));
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
    // CSV tetap menggunakan proses yang sudah ada.
    if ($extension === 'csv') {
        $file = fopen($path, 'r');

        if ($file === false) {
            throw new \RuntimeException(
                'File CSV tidak dapat dibaca.'
            );
        }

        try {
            $header = fgetcsv($file);

            if ($header === false || empty($header)) {
                throw new \RuntimeException(
                    'File CSV kosong atau tidak memiliki header.'
                );
            }

            // Hapus BOM jika file CSV dibuat menggunakan Excel.
            $header[0] = preg_replace(
                '/^\xEF\xBB\xBF/',
                '',
                (string) $header[0]
            );

            $header = array_map(
                fn ($value) => trim((string) $value),
                $header
            );

            if (
                count(array_filter(
                    $header,
                    fn ($value) => $value !== ''
                )) !== count($header)
            ) {
                throw new \RuntimeException(
                    'Semua kolom CSV harus memiliki nama.'
                );
            }

            if (count(array_unique($header)) !== count($header)) {
                throw new \RuntimeException(
                    'Nama kolom CSV tidak boleh duplikat.'
                );
            }

            $rows = [];

            while (($row = fgetcsv($file)) !== false) {
                if (
                    count(array_filter(
                        $row,
                        fn ($value) =>
                            $value !== null && trim((string) $value) !== ''
                    )) === 0
                ) {
                    continue;
                }

                if (count($row) !== count($header)) {
                    throw new \RuntimeException(
                        'Jumlah kolom pada baris CSV tidak konsisten.'
                    );
                }

                $rows[] = array_combine($header, $row);
            }

            return $rows;
        } finally {
            fclose($file);
        }
    }

    // Membaca workbook Excel, termasuk seluruh sheet.
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

    $bestSheet = null;
    $bestHeaderRow = null;
    $bestScore = 0;

    foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
        $highestRow = $worksheet->getHighestDataRow();
        $highestColumn = $worksheet->getHighestDataColumn();

        // Header biasanya berada di bagian awal worksheet.
        $scanLimit = min($highestRow, 50);

        for ($rowNumber = 1; $rowNumber <= $scanLimit; $rowNumber++) {
            $values = $worksheet->rangeToArray(
                "A{$rowNumber}:{$highestColumn}{$rowNumber}",
                null,
                true,
                true,
                false
            )[0];

            $nonEmpty = array_filter(
                $values,
                fn ($value) =>
                    $value !== null && trim((string) $value) !== ''
            );

            // Baris judul biasanya hanya memiliki satu sel terisi.
            if (count($nonEmpty) < 3) {
                continue;
            }

            // Header harus berisi nama kolom, bukan formula.
            $textHeaders = array_filter(
                $nonEmpty,
                fn ($value) =>
                    is_string($value)
                    && !str_starts_with(trim($value), '=')
                    && !is_numeric($value)
            );

            $score = count($textHeaders) * 10
                + count($nonEmpty);

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestSheet = $worksheet;
                $bestHeaderRow = $rowNumber;
            }
        }
    }

    if ($bestSheet === null || $bestHeaderRow === null) {
        $spreadsheet->disconnectWorksheets();

        throw new \RuntimeException(
            'Header tabel tidak ditemukan pada workbook Excel.'
        );
    }

    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
        $bestSheet->getHighestDataColumn()
    );

    $header = [];

    for ($column = 1; $column <= $highestColumnIndex; $column++) {
        $value = $bestSheet->getCell([$column, $bestHeaderRow])
            ->getCalculatedValue();

        $header[] = trim((string) ($value ?? ''));
    }

    // Buang kolom kosong di bagian akhir header.
    while (!empty($header) && end($header) === '') {
        array_pop($header);
    }

    if (
        count($header) < 3
        || in_array('', $header, true)
        || count(array_unique($header)) !== count($header)
    ) {
        $spreadsheet->disconnectWorksheets();

        throw new \RuntimeException(
            'Header Excel kosong, tidak lengkap, atau memiliki nama kolom duplikat.'
        );
    }

    // Nama kolom dibuat konsisten sebagai key JSON.
    $normalizedHeader = [];
    $usedNames = [];

    foreach ($header as $index => $name) {
        $key = trim($name);

        if ($key === '') {
            $spreadsheet->disconnectWorksheets();

            throw new \RuntimeException(
                'Nama kolom Excel tidak boleh kosong.'
            );
        }

        $key = preg_replace('/\s+/', '_', $key);
        $key = mb_strtolower($key);

        if (isset($usedNames[$key])) {
            $spreadsheet->disconnectWorksheets();

            throw new \RuntimeException(
                'Nama kolom Excel tidak boleh duplikat.'
            );
        }

        $usedNames[$key] = true;
        $normalizedHeader[$index] = $key;
    }

    $rows = [];
    $highestRow = $bestSheet->getHighestDataRow();

    for (
        $rowNumber = $bestHeaderRow + 1;
        $rowNumber <= $highestRow;
        $rowNumber++
    ) {
        $row = [];
        $nonEmptyCount = 0;

        foreach ($normalizedHeader as $column => $key) {
            $cell = $bestSheet->getCell([
                $column + 1,
                $rowNumber,
            ]);

            $value = $cell->getCalculatedValue();

            // Jangan menyimpan teks formula mentah jika tidak
            // ada nilai hasil perhitungan yang tersedia.
            if (
                is_string($value)
                && str_starts_with(trim($value), '=')
            ) {
                $value = null;
            }

            if ($value instanceof \DateTimeInterface) {
                $value = $value->format('Y-m-d H:i:s');
            }

            if ($value !== null && $value !== '') {
                $nonEmptyCount++;
            }

            $row[$key] = $value;
        }

        // Lewati baris kosong dan informasi periode di bagian bawah.
        if ($nonEmptyCount === 0) {
            continue;
        }

        $firstValue = mb_strtolower(
            trim((string) reset($row))
        );

        if (str_starts_with($firstValue, 'periode:')) {
            continue;
        }

        $rows[] = $row;

        if (count($rows) > 10000) {
            $spreadsheet->disconnectWorksheets();

            throw new \RuntimeException(
                'Dataset maksimal berisi 10.000 baris.'
            );
        }
    }

    $spreadsheet->disconnectWorksheets();

    if (empty($rows)) {
        throw new \RuntimeException(
            'Tidak ada baris data setelah header Excel.'
        );
    }

    return $rows;
}
}