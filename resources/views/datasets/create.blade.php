<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Dataset | TanyAku</title>

    <style>
        body {
            margin: 0;
            padding: 30px 16px;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #202437;
        }

        .panel {
            max-width: 650px;
            margin: auto;
            padding: 28px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        label {
            display: block;
            margin: 18px 0 8px;
            font-weight: 600;
        }

        input, textarea {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            border: 1px solid #d8dbea;
            border-radius: 7px;
            font: inherit;
        }

        input[type="file"] {
            background: #fafaff;
        }

        .help {
            color: #656b80;
            font-size: 13px;
            margin-top: 7px;
        }

        button, .back {
            display: inline-block;
            margin-top: 22px;
            padding: 11px 15px;
            border: 0;
            border-radius: 7px;
            text-decoration: none;
            cursor: pointer;
            font: inherit;
        }

        button {
            background: #5146d8;
            color: white;
        }

        .back {
            background: #eef0f8;
            color: #30344b;
        }

        .error {
            color: #a12626;
            font-size: 14px;
            margin-top: 6px;
        }

        .success {
            color: #166534;
            background: #dcfce7;
            padding: 12px;
            border-radius: 7px;
        }

        .error-box {
            color: #991b1b;
            background: #fee2e2;
            padding: 12px;
            border-radius: 7px;
        }
    </style>
</head>

<body>
<div class="panel">
    <h1>Tambah Dataset</h1>
    <p>Unggah file CSV atau Excel untuk menyimpan data ke sistem TanyAku.</p>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="error-box">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="error-box">
            <strong>Periksa kembali input kamu:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('datasets.store') }}"
        enctype="multipart/form-data"
    >
        @csrf

        <label for="name">Nama Dataset *</label>
        <input
            id="name"
            name="name"
            value="{{ old('name') }}"
            required
            maxlength="200"
            placeholder="Contoh: Data Penjualan Oktober"
        >

        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="description">Deskripsi</label>
        <textarea
            id="description"
            name="description"
            rows="4"
            maxlength="2000"
            placeholder="Jelaskan isi atau tujuan dataset"
        >{{ old('description') }}</textarea>

        @error('description')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="file">Pilih File Dataset *</label>
        <input
            type="file"
            id="file"
            name="file"
            accept=".csv,.xlsx,.xls"
            required
        >

        <p class="help">
            Format yang didukung: CSV, XLSX, dan XLS.
            Ukuran maksimal 10 MB. Baris pertama harus berisi nama kolom.
        </p>

        @error('file')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Unggah Dataset</button>
        <a class="back" href="{{ route('datasets.index') }}">Kembali</a>
    </form>
</div>
</body>
</html>