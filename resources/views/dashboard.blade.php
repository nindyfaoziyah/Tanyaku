<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | TanyAku</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f7f7f9;
            color: #29252b;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 245px;
            background: #641b32;
            color: white;
            padding: 28px 18px;
            flex-shrink: 0;
        }

        .brand {
            font-size: 25px;
            font-weight: bold;
            padding: 0 12px;
            margin-bottom: 8px;
        }

        .brand-description {
            padding: 0 12px;
            color: #e8cbd4;
            font-size: 12px;
            margin-bottom: 38px;
        }

        .menu-label {
            padding: 0 12px;
            color: #e8cbd4;
            font-size: 11px;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .menu {
            display: block;
            padding: 13px 14px;
            margin-bottom: 8px;
            color: #f5e9ed;
            text-decoration: none;
            border-radius: 9px;
            font-size: 14px;
        }

        .menu.active,
        .menu:hover {
            background: #852c49;
            color: white;
        }

        .sidebar-bottom {
            margin-top: 45px;
            padding: 0 12px;
            font-size: 12px;
            line-height: 1.7;
            color: #e8cbd4;
        }

        .main {
            flex: 1;
            min-width: 0;
        }

        .topbar {
            min-height: 82px;
            background: white;
            border-bottom: 1px solid #ece8eb;
            padding: 20px 34px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .topbar h1 {
            font-size: 22px;
            margin: 0 0 6px;
        }

        .topbar p {
            margin: 0;
            color: #88818a;
            font-size: 13px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f4e5ea;
            color: #641b32;
            font-weight: bold;
        }

        .profile-name {
            font-size: 13px;
            font-weight: bold;
        }

        .profile-role {
            font-size: 12px;
            color: #88818a;
            margin-top: 5px;
        }

        .content {
            padding: 32px 34px;
            max-width: 1500px;
            margin: auto;
        }

        .welcome {
            padding: 28px;
            border-radius: 14px;
            background: linear-gradient(120deg, #641b32, #8a3550);
            color: white;
            margin-bottom: 28px;
        }

        .welcome h2 {
            font-size: 24px;
            margin: 0 0 10px;
        }

        .welcome p {
            margin: 0;
            color: #f2dfe5;
            font-size: 14px;
            line-height: 1.7;
        }

        .section-title {
            font-size: 17px;
            margin: 0 0 18px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 23px;
            border-radius: 12px;
            border: 1px solid #eee9ec;
        }

        .stat-label {
            font-size: 13px;
            color: #817983;
            margin-bottom: 16px;
        }

        .stat-number {
            font-size: 30px;
            font-weight: bold;
            color: #641b32;
            margin-bottom: 8px;
        }

        .stat-description {
            color: #948c95;
            font-size: 12px;
        }

        .table-card {
            background: white;
            border: 1px solid #eee9ec;
            border-radius: 12px;
            overflow: hidden;
        }

        .table-heading {
            padding: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .table-heading h3 {
            margin: 0;
            font-size: 16px;
        }

        .view-all {
            color: #812b47;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        th {
            background: #faf7f8;
            color: #817983;
            font-size: 12px;
            font-weight: normal;
            text-align: left;
            padding: 15px 22px;
        }

        td {
            padding: 17px 22px;
            border-top: 1px solid #f0ecef;
            font-size: 13px;
        }

        .dataset-name {
            font-weight: bold;
            color: #39313a;
        }

        .muted {
            color: #918994;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            background: #e7f7ed;
            color: #247a46;
        }

        .badge-other {
            background: #fff1d9;
            color: #95651b;
        }

        .empty {
            text-align: center;
            padding: 38px 20px;
            color: #918994;
            line-height: 1.8;
        }

        .footer {
            padding: 25px 0 5px;
            text-align: center;
            color: #a099a1;
            font-size: 12px;
        }

        .logout-form {
            margin-top: 28px;
            padding: 0 8px;
        }

        .logout-button {
            width: 100%;
            padding: 12px;
            background: transparent;
            border: 1px solid #a65d75;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            text-align: left;
        }

        .logout-button:hover {
            background: #852c49;
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 200px;
            }

            .content {
                padding: 25px 20px;
            }

            .topbar {
                padding: 20px;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .layout {
                display: block;
            }

            .sidebar {
                width: 100%;
                padding: 18px;
            }

            .brand {
                margin-bottom: 5px;
            }

            .brand-description {
                margin-bottom: 20px;
            }

            .sidebar-bottom,
            .menu-label {
                display: none;
            }

            .menu {
                display: inline-block;
                margin: 10px 4px 0 0;
                padding: 10px;
            }

            .logout-form {
                margin-top: 12px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .welcome {
                padding: 22px;
            }

            .welcome h2 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
<div class="layout">

    <aside class="sidebar">
        <div class="brand">TanyAku</div>
        <div class="brand-description">Data Management & Analytics</div>

        <div class="menu-label">MENU UTAMA</div>

        <a href="{{ route('dashboard') }}" class="menu active">
            Dashboard
        </a>

        @if ($user->role?->permissions?->contains('code', 'dataset.view'))
            <a href="{{ route('datasets.index') }}" class="menu">
                Kelola Dataset
            </a>
        @endif

        <div class="sidebar-bottom">
            <div>Pengguna aktif</div>
            <strong>{{ $user->name }}</strong>
            <div>{{ $departmentName }}</div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="logout-button">
                Logout
            </button>
        </form>
    </aside>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Ringkasan data dan aktivitas TanyAku</p>
            </div>

            <div class="profile">
                <div class="avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div>
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-role">
                        {{ $user->role?->name ?? 'Pengguna' }}
                        · {{ $departmentName }}
                    </div>
                </div>
            </div>
        </header>

        <div class="content">

            <section class="welcome">
                <h2>Selamat datang, {{ $user->name }}!</h2>
                <p>
                    Kelola dan pantau data kamu melalui satu platform.
                    Berikut ringkasan dataset yang dapat kamu akses.
                </p>
            </section>

            <h2 class="section-title">Ringkasan Data</h2>

            <section class="stats">

                <div class="stat-card">
                    <div class="stat-label">Total Dataset</div>
                    <div class="stat-number">
                        {{ number_format($totalDatasets) }}
                    </div>
                    <div class="stat-description">
                        Dataset yang dapat kamu akses
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Total Record</div>
                    <div class="stat-number">
                        {{ number_format($totalRecords) }}
                    </div>
                    <div class="stat-description">
                        Baris data dalam dataset yang dapat kamu akses
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Dataset Siap Digunakan</div>
                    <div class="stat-number">
                        {{ number_format($readyDatasets) }}
                    </div>
                    <div class="stat-description">
                        Dataset dengan status READY
                    </div>
                </div>

            </section>

            <section class="table-card">

                <div class="table-heading">
                    <h3>Dataset Terbaru</h3>

                    @if ($user->role?->permissions?->contains('code', 'dataset.view'))
                        <a href="{{ route('datasets.index') }}" class="view-all">
                            Lihat semua
                        </a>
                    @endif
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Dataset</th>
                                <th>Divisi</th>
                                <th>Pengunggah</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($recentDatasets as $dataset)
                                <tr>
                                    <td>
                                        <div class="dataset-name">
                                            {{ $dataset->name }}
                                        </div>
                                        <div class="muted">
                                            {{ strtoupper($dataset->file_type) }}
                                        </div>
                                    </td>

                                    <td>
                                        {{ $dataset->department?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $dataset->uploader?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $dataset->created_at?->format('d M Y') ?? '-' }}
                                    </td>

                                    <td>
                                        @if ($dataset->status === 'READY')
                                            <span class="badge">Siap digunakan</span>
                                        @else
                                            <span class="badge badge-other">
                                                {{ $dataset->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty">
                                        Belum ada dataset yang tersedia.<br>
                                        Dataset yang kamu unggah akan muncul di sini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </section>

            <div class="footer">
                TanyAku · Data Management & Analytics Platform
            </div>

        </div>
    </main>
</div>
</body>
</html>