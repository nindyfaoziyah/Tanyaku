<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Dataset | TanyAku</title>

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
        }

        label {
            display: block;
            margin: 18px 0 8px;
            font-weight: 600;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d8dbea;
            border-radius: 7px;
            font: inherit;
        }

        button, a {
            display: inline-block;
            margin-top: 22px;
            padding: 11px 15px;
            border: 0;
            border-radius: 7px;
            text-decoration: none;
            cursor: pointer;
        }

        button { background: #5146d8; color: white; }
        a { background: #eef0f8; color: #30344b; }

        .error { color: #a12626; font-size: 14px; }
    </style>
</head>

<body>
<div class="panel">
    <h1>Edit Dataset</h1>

    <form method="POST" action="{{ route('datasets.update', $dataset) }}">
        @csrf
        @method('PUT')

        <label for="name">Nama Dataset *</label>
        <input
            id="name"
            name="name"
            value="{{ old('name', $dataset->name) }}"
            maxlength="255"
            required
        >
        @error('name')
            <p class="error">{{ $message }}</p>
        @enderror

        <label for="description">Deskripsi</label>
        <textarea
            id="description"
            name="description"
            rows="5"
            maxlength="2000"
        >{{ old('description', $dataset->description) }}</textarea>
        @error('description')
            <p class="error">{{ $message }}</p>
        @enderror

        <button type="submit">Simpan Perubahan</button>
        <a href="{{ route('datasets.show', $dataset) }}">Batal</a>
    </form>
</div>
</body>
</html>