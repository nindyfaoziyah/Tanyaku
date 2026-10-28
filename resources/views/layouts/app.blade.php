<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Workspace') | TanyAku</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" aria-label="Navigasi utama">
        <a class="brand" href="{{ route('dashboard') }}" aria-label="TanyAku Beranda">
            <span class="brand-mark" aria-hidden="true"><span>T</span><i>•••</i></span>
            <span><strong>TanyAku</strong><small>DATA WORKSPACE</small></span>
        </a>

        <div class="nav-caption">WORKSPACE</div>
        <nav class="primary-nav">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" href="{{ route('dashboard') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/></svg>
                <span>Beranda</span>
            </a>
            <span class="nav-link is-disabled" aria-disabled="true" title="Belum tersedia">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 8v4l2.5 1.5"/><circle cx="12" cy="12" r="8.5"/></svg>
                <span>Riwayat</span><small>Segera</small>
            </span>
            <span class="nav-link is-disabled" aria-disabled="true" title="Belum tersedia">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c.7-3.1 3.1-5 7-5s6.3 1.9 7 5"/></svg>
                <span>Profil</span><small>Segera</small>
            </span>
            @if ($user->role?->permissions?->contains('code', 'dataset.view'))
                <a class="nav-link {{ request()->routeIs('datasets.*') ? 'is-active' : '' }}" href="{{ route('datasets.index') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v7c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 12v7c0 1.7 3.6 3 8 3s8-1.3 8-3v-7"/></svg>
                    <span>Kelola Dataset</span>
                </a>
            @endif
        </nav>

        <div class="sidebar-spacer"></div>
        <div class="workspace-note">
            <span class="note-dot"></span>
            <span><strong>{{ $departmentName }}</strong><small>Workspace divisi</small></span>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button class="logout-link" type="submit">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4H5.5A1.5 1.5 0 0 0 4 5.5v13A1.5 1.5 0 0 0 5.5 20H10M14 16l4-4-4-4M18 12H9"/></svg>
                Keluar
            </button>
        </form>
    </aside>

    <div class="main-shell">
        <header class="topbar">
            <div class="breadcrumbs"><span>Workspace</span><span class="crumb-slash">/</span><strong>{{ $departmentName }}</strong><span class="crumb-chevron" aria-hidden="true">⌄</span></div>
            <div class="profile-actions">
                <span class="notification-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><i></i></span>
                <div class="topbar-user">
                    <div class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</div>
                    <div class="topbar-user-copy"><strong>{{ $user->name }}</strong><span>{{ $user->role?->name ?? 'Pengguna' }}</span></div>
                </div>
            </div>
        </header>

        <main class="page-content">
            @if (session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error" role="alert">{{ session('error') }}</div>
            @endif
            @yield('content')
            <footer class="page-footer">TanyAku <span>·</span> Data Management &amp; Analytics</footer>
        </main>
    </div>
</div>
</body>
</html>
