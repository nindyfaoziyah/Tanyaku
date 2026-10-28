@extends('layouts.app')

@section('title', 'Tambah Dataset')

@section('content')
    <div class="page-heading">
        <div><p class="eyebrow">DATA WORKSPACE</p><h1>Tambah Dataset</h1><p>Unggah file CSV atau Excel untuk menyimpan data di TanyAku.</p></div>
        <a class="button button-secondary" href="{{ route('datasets.index') }}">Kembali ke daftar</a>
    </div>
    <section class="content-panel form-panel">
        @if ($errors->any())
            <div class="alert alert-error" role="alert"><strong>Periksa kembali input kamu:</strong><ul class="error-list">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <form class="form-grid" method="POST" action="{{ route('datasets.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-field">
                <label for="name">Nama Dataset <span aria-hidden="true">*</span></label>
                <input id="name" name="name" value="{{ old('name') }}" required maxlength="200" placeholder="Contoh: Data Penjualan Oktober" aria-describedby="name-error">
                @error('name')<div id="name-error" class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-field">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="4" maxlength="2000" placeholder="Jelaskan isi atau tujuan dataset">{{ old('description') }}</textarea>
                @error('description')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-field">
                <label for="file">Pilih File Dataset <span aria-hidden="true">*</span></label>
                <input type="file" id="file" name="file" accept=".csv,.xlsx,.xls" required aria-describedby="file-help file-error">
                <p id="file-help" class="form-help">Format yang didukung: CSV, XLSX, dan XLS. Ukuran maksimal 10 MB. Baris pertama harus berisi nama kolom.</p>
                @error('file')<div id="file-error" class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-actions"><button class="button button-primary" type="submit">Unggah Dataset</button><a class="button button-secondary" href="{{ route('datasets.index') }}">Batal</a></div>
        </form>
    </section>
@endsection
