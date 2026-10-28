@extends('layouts.app')

@section('title', 'Detail Dataset')

@section('content')
    <div class="page-heading">
        <div><p class="eyebrow">DATA WORKSPACE / DETAIL</p><h1>{{ $dataset->name }}</h1><p>Informasi dan pratinjau dataset tersimpan.</p></div>
        <div class="toolbar-actions">
            @if (auth()->user()->role?->permissions?->contains('code', 'dataset.update'))
                <a class="button button-primary" href="{{ route('datasets.edit', $dataset) }}">Edit Dataset</a>
            @endif
            <a class="button button-secondary" href="{{ route('datasets.index') }}">Kembali ke Daftar</a>
        </div>
    </div>

    <section class="content-panel">
        <div class="dataset-details">
            <div class="detail-row"><div class="detail-label">Nama Dataset</div><div class="detail-value">{{ $dataset->name }}</div></div>
            <div class="detail-row"><div class="detail-label">Divisi</div><div class="detail-value">{{ $dataset->department?->name ?? '-' }}</div></div>
            <div class="detail-row"><div class="detail-label">Pengunggah</div><div class="detail-value">{{ $dataset->uploader?->name ?? '-' }}</div></div>
            <div class="detail-row"><div class="detail-label">Status</div><div class="detail-value"><span class="status-text {{ $dataset->status === 'READY' ? '' : 'status-other' }}">{{ $dataset->status === 'READY' ? 'Siap digunakan' : $dataset->status }}</span></div></div>
            <div class="detail-row"><div class="detail-label">Deskripsi</div><div class="detail-value">{{ $dataset->description ?: 'Belum ada deskripsi.' }}</div></div>
            <div class="detail-row"><div class="detail-label">Tanggal Unggah</div><div class="detail-value">{{ $dataset->created_at?->format('d M Y, H:i') ?? '-' }}</div></div>
            <div class="detail-row"><div class="detail-label">Nama File</div><div class="detail-value">{{ $dataset->original_filename ?: 'Belum ada file.' }}</div></div>
            <div class="detail-row"><div class="detail-label">Jenis File</div><div class="detail-value">{{ strtoupper($dataset->file_type ?: '-') }}</div></div>
        </div>
    </section>

    @php
        $firstRecord = $records->first();
        $columns = $firstRecord ? array_keys((array) $firstRecord->record_data) : [];
    @endphp
    <section class="content-panel" style="margin-top: 18px">
        <div class="preview-heading">
            <div><h2>Preview Data</h2><p>Pratinjau isi dataset yang tersimpan di database.</p></div>
            <span class="record-count">{{ number_format($records->total()) }} baris data</span>
        </div>
        @if ($records->isEmpty())
            <div class="empty-state"><span class="empty-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4.5h9l5 5v10A1.5 1.5 0 0 1 17.5 21h-11A1.5 1.5 0 0 1 5 19.5z"/><path d="M14 4.5v5h5M8.5 14h7"/></svg></span><strong>Belum ada data untuk ditampilkan.</strong><p>Pastikan file berhasil diproses saat diunggah.</p></div>
        @else
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr>@foreach ($columns as $column)<th>{{ str_replace('_', ' ', ucfirst($column)) }}</th>@endforeach</tr></thead>
                    <tbody>
                    @foreach ($records as $record)
                        @php($row = (array) $record->record_data)
                        <tr>@foreach ($columns as $column)
                            @php($value = $row[$column] ?? '-')
                            <td>{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : ($value === '' ? '-' : $value) }}</td>
                        @endforeach</tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $records->links() }}</div>
        @endif
    </section>
@endsection
