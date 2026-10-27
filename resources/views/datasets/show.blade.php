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
</body>
</html>