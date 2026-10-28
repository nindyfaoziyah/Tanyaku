@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="welcome-heading">
        <div>
            <h1>Halo, {{ $user->name }}</h1>
            <p class="welcome-copy">Keputusan yang lebih baik, dimulai dari data.</p>
        </div>
        <span class="department-badge"><span class="badge-dot"></span>{{ $departmentName }}</span>
    </section>

    <section class="hero-banner" aria-label="Insight berbasis data">
        <div class="hero-copy">
            <p class="hero-eyebrow"><span></span>INSIGHT BERBASIS DATA</p>
            <h2>Dari data menjadi arah.</h2>
            <p>Kenali pola, temukan peluang, dan ambil langkah berikutnya bersama TanyAku.</p>
            @if ($user->role?->permissions?->contains('code', 'dataset.view'))
                <a class="hero-cta" href="{{ route('datasets.index') }}">Mulai analisis <span aria-hidden="true">→</span></a>
            @else
                <span class="hero-cta hero-cta-disabled">Mulai analisis <span aria-hidden="true">→</span></span>
            @endif
        </div>
        <i class="hero-glow" aria-hidden="true"></i>
        <div class="hero-art" aria-hidden="true">
            <div class="analytics-card">
                <div class="chart-area"><span class="chart-grid grid-one"></span><span class="chart-grid grid-two"></span><span class="chart-grid grid-three"></span>
                    <i class="bar bar-one"></i><i class="bar bar-two"></i><i class="bar bar-three"></i><i class="bar bar-four"></i>
                </div>
                <span class="art-star">★</span>
            </div>
            <div class="floating-chip"><span></span><strong>Live Metrics</strong></div>
        </div>
    </section>

    <section class="dashboard-section">
        <div class="section-heading">
            <div><h2>Ringkasan Metrik Terkini</h2></div>
            <span class="updated-label"><i></i>Data terkini</span>
        </div>
        <div class="metric-grid">
            <article class="metric-card">
                <div class="metric-top"><span>Total Dataset</span><span class="metric-icon icon-cyan"><svg viewBox="0 0 24 24" aria-hidden="true"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v7c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 12v7c0 1.7 3.6 3 8 3s8-1.3 8-3v-7"/></svg></span></div>
                <strong class="metric-value">{{ number_format($totalDatasets) }}</strong><p>Dataset yang dapat kamu akses</p>
            </article>
            <article class="metric-card">
                <div class="metric-top"><span>Total Record</span><span class="metric-icon icon-blue"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5.5h16M4 12h16M4 18.5h16"/><circle cx="7" cy="5.5" r="1"/><circle cx="7" cy="12" r="1"/><circle cx="7" cy="18.5" r="1"/></svg></span></div>
                <strong class="metric-value">{{ number_format($totalRecords) }}</strong><p>Baris data di dataset yang dapat kamu akses</p>
            </article>
            <article class="metric-card">
                <div class="metric-top"><span>Dataset Siap Digunakan</span><span class="metric-icon icon-green"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12.5 4.5 4.5L19 7"/><circle cx="12" cy="12" r="9"/></svg></span></div>
                <strong class="metric-value">{{ number_format($readyDatasets) }}</strong><p>Dataset dengan status siap digunakan</p>
            </article>
        </div>
    </section>

    <section class="dashboard-section activity-section">
        <div class="section-heading">
            <div><h2>Aktivitas Analisis Terbaru</h2></div>
            @if ($user->role?->permissions?->contains('code', 'dataset.view'))
                <a class="text-link" href="{{ route('datasets.index') }}">Lihat Semua <span aria-hidden="true">→</span></a>
            @endif
        </div>
        <div class="activity-list">
            @forelse ($recentDatasets as $dataset)
                <article class="activity-card">
                    <div class="activity-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4.5h9l5 5v10A1.5 1.5 0 0 1 17.5 21h-11A1.5 1.5 0 0 1 5 19.5z"/><path d="M14 4.5v5h5M8.5 14h7M8.5 17h7"/></svg></div>
                    <div class="activity-info"><strong>{{ $dataset->name }}</strong><span>{{ $dataset->department?->name ?? $departmentName }} <i>·</i> {{ $dataset->created_at?->format('d M Y') ?? '-' }}</span></div>
                    <span class="status-badge {{ $dataset->status === 'READY' ? 'status-ready' : 'status-other' }}"><i></i>{{ $dataset->status === 'READY' ? 'Siap digunakan' : $dataset->status }}</span>
                    @if ($user->role?->permissions?->contains('code', 'dataset.view'))
                        <a class="button button-quiet activity-action" href="{{ route('datasets.show', $dataset) }}">Detail</a>
                    @endif
                </article>
            @empty
                <div class="empty-state">
                    <span class="empty-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4.5h9l5 5v10A1.5 1.5 0 0 1 17.5 21h-11A1.5 1.5 0 0 1 5 19.5z"/><path d="M14 4.5v5h5M8.5 14h7M8.5 17h4"/></svg></span>
                    <strong>Belum ada dataset yang tersedia.</strong>
                    <p>Dataset workspace kamu akan muncul di sini setelah diunggah.</p>
                    @if ($user->role?->permissions?->contains('code', 'dataset.create'))
                        <a class="button button-primary" href="{{ route('datasets.create') }}">Unggah dataset</a>
                    @endif
                </div>
            @endforelse
        </div>
    </section>
@endsection
