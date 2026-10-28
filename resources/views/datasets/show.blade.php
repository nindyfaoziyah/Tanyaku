<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Dataset | TanyAku</title>

    <style>
        body {
            margin: 0;
            padding: 30px 16px;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #202437;
        }

        .panel {
            max-width: 800px;
            margin: auto;
            padding: 28px;
            background: white;
            border-radius: 12px;
        }

        .row {
            padding: 14px 0;
            border-bottom: 1px solid #eceef5;
        }

        .label { color: #687086; font-size: 13px; }
        .value { margin-top: 6px; overflow-wrap: anywhere; }

        a {
            display: inline-block;
            margin: 20px 8px 0 0;
            padding: 11px 15px;
            border-radius: 7px;
            background: #5146d8;
            color: white;
            text-decoration: none;
        }

        .secondary { background: #eef0f8; color: #30344b; }
    </style>
</head>

<body>
<div class="panel">
    <h1>{{ $dataset->name }}</h1>
    <p>Informasi detail dataset TanyAku.</p>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <div class="row">
        <div class="label">Nama Dataset</div>
        <div class="value">{{ $dataset->name }}</div>
    </div>

    <div class="row">
        <div class="label">Divisi</div>
        <div class="value">{{ $dataset->department->name ?? '-' }}</div>
    </div>

    <div class="row">
        <div class="label">Pengunggah</div>
        <div class="value">{{ $dataset->uploader->name ?? '-' }}</div>
    </div>

    <div class="row">
        <div class="label">Deskripsi</div>
        <div class="value">{{ $dataset->description ?: 'Belum ada deskripsi.' }}</div>
    </div>

    <div class="row">
        <div class="label">Nama File</div>
        <div class="value">{{ $dataset->original_filename ?: 'Belum ada file.' }}</div>
    </div>

    <div class="row">
        <div class="label">Jenis File</div>
        <div class="value">{{ $dataset->file_type ?: '-' }}</div>
    </div>

    <div class="row">
        <div class="label">Status</div>
        <div class="value">{{ $dataset->status }}</div>
    </div>

    <div class="row">
        <div class="label">Jumlah Record</div>
        <div class="value">{{ $dataset->records->count() }}</div>
    </div>

    @if (auth()->user()->role->permissions->contains('code', 'dataset.update'))
        <a href="{{ route('datasets.edit', $dataset) }}">Edit Dataset</a>
    @endif

    <a href="{{ route('datasets.index') }}" class="secondary">Kembali ke Daftar</a>
</div>

@php
    $previewRows = $records->getCollection();

    $columns = $previewRows->isNotEmpty()
        ? array_keys((array) $previewRows->first()->record_data)
        : [];
@endphp

<section class="mt-8 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-800">
                Preview Data
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Pratinjau isi dataset yang tersimpan di database.
            </p>
        </div>

        <span class="rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
            {{ $records->total() }} baris data
        </span>
    </div>

    @if ($records->isEmpty())
        <div class="rounded-lg border border-dashed border-gray-300 px-4 py-10 text-center">
            <p class="font-medium text-gray-700">
                Belum ada data untuk ditampilkan.
            </p>

            <p class="mt-1 text-sm text-gray-500">
                Pastikan file berhasil diproses saat diunggah.
            </p>
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full min-w-max border-collapse text-left text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        @php
                            $firstRecord = (array) $records->first()->record_data;
                            $columns = array_keys($firstRecord);
                        @endphp

                        @foreach ($columns as $column)
                            <th class="whitespace-nowrap border-b border-gray-200 px-4 py-3 font-semibold">
                                {{ str_replace('_', ' ', ucfirst($column)) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach ($records as $record)
                        @php
                            $row = (array) $record->record_data;
                        @endphp

                        <tr class="transition-colors hover:bg-gray-50">
                            @foreach ($columns as $column)
                                <td class="max-w-xs whitespace-normal break-words px-4 py-3 text-gray-600">
                                    @php
                                        $value = $row[$column] ?? '-';
                                    @endphp

                                    @if (is_array($value))
                                        {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}
                                    @else
                                        {{ $value === '' ? '-' : $value }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $records->links() }}
        </div>
    @endif
</section>

</body>
</html>