@extends('layouts.app')

@section('title', 'Dataset')

@section('content')
    <div class="page-heading">
        <div><p class="eyebrow">DATA WORKSPACE</p><h1>Daftar Dataset</h1><p>Kelola dataset yang tersedia untuk divisi kamu.</p></div>
        @if (auth()->user()->role?->permissions?->contains('code', 'dataset.create'))
            <a href="{{ route('datasets.create') }}" class="button button-primary">+ Tambah Dataset</a>
        @endif
    </div>

    <section class="content-panel">
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Nama Dataset</th><th>Divisi</th><th>Pengunggah</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse ($datasets as $dataset)
                    <tr>
                        <td><strong>{{ $dataset->name }}</strong><br><span class="muted">{{ strtoupper($dataset->file_type) }}</span></td>
                        <td>{{ $dataset->department?->name ?? '-' }}</td>
                        <td>{{ $dataset->uploader?->name ?? '-' }}</td>
                        <td><span class="status-text {{ $dataset->status === 'READY' ? '' : 'status-other' }}">{{ $dataset->status === 'READY' ? 'Siap digunakan' : $dataset->status }}</span></td>
                        <td><div class="actions">
                            <a class="button button-quiet" href="{{ route('datasets.show', $dataset) }}">Detail</a>
                            @if (auth()->user()->role?->permissions?->contains('code', 'dataset.update'))
                                <a class="button button-secondary" href="{{ route('datasets.edit', $dataset) }}">Edit</a>
                            @endif
                            @if (auth()->user()->role?->permissions?->contains('code', 'dataset.delete'))
                                <form method="POST" action="{{ route('datasets.destroy', $dataset) }}" onsubmit="return confirm('Yakin ingin menghapus dataset ini?')">
                                    @csrf @method('DELETE')
                                    <button class="button button-danger" type="submit">Hapus</button>
                                </form>
                            @endif
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-cell">Belum ada dataset yang tersedia.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $datasets->links() }}</div>
    </section>
@endsection
