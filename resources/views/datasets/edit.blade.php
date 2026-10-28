@extends('layouts.app')

@section('title', 'Edit Dataset')

@section('content')
    <div class="page-heading">
        <div><p class="eyebrow">DATA WORKSPACE</p><h1>Edit Dataset</h1><p>Perbarui informasi untuk {{ $dataset->name }}.</p></div>
        <a class="button button-secondary" href="{{ route('datasets.show', $dataset) }}">Kembali ke detail</a>
    </div>
    <section class="content-panel form-panel">
        @if ($errors->any())
            <div class="alert alert-error" role="alert"><strong>Periksa kembali input kamu:</strong><ul class="error-list">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <form class="form-grid" method="POST" action="{{ route('datasets.update', $dataset) }}">
            @csrf @method('PUT')
            <div class="form-field">
                <label for="name">Nama Dataset <span aria-hidden="true">*</span></label>
                <input id="name" name="name" value="{{ old('name', $dataset->name) }}" maxlength="255" required>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-field">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="5" maxlength="2000">{{ old('description', $dataset->description) }}</textarea>
                @error('description')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-actions"><button class="button button-primary" type="submit">Simpan Perubahan</button><a class="button button-secondary" href="{{ route('datasets.show', $dataset) }}">Batal</a></div>
        </form>
    </section>
@endsection
