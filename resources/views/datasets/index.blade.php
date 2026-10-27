<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dataset | TanyAku</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #202437;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 5%;
            background: white;
            border-bottom: 1px solid #e5e7ef;
        }

        header h1 { color: #5146d8; margin: 0; }

        main {
            max-width: 1100px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .panel {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 20px #2024370a;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        a, button {
            display: inline-block;
            padding: 10px 14px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .primary { background: #5146d8; color: white; }
        .secondary { background: #eef0f8; color: #30344b; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 10px;
            text-align: left;
            border-bottom: 1px solid #eceef5;
        }

        th { color: #687086; font-size: 13px; }
        .empty { text-align: center; padding: 30px; color: #687086; }

        .message {
            margin-bottom: 18px;
            padding: 12px;
            background: #e7f8ee;
            color: #187443;
            border-radius: 8px;
        }

        .actions { display: flex; flex-wrap: wrap; gap: 6px; }

        .logout { background: #fceaea; color: #a12626; }

        @media (max-width: 700px) {
            .table-wrap { overflow-x: auto; }
            table { min-width: 650px; }
        }
    </style>
</head>

<body>
<header>
    <h1>TanyAku</h1>

    <div>
        <a href="{{ route('dashboard') }}" class="secondary">Dashboard</a>

        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button class="logout" type="submit">Logout</button>
        </form>
    </div>
</header>

<main>
    <section class="panel">
        <div class="toolbar">
            <div>
                <h2>Daftar Dataset</h2>
                <p>Kelola informasi dataset sesuai hak akses divisi kamu.</p>
            </div>

            @if (auth()->user()->role->permissions->contains('code', 'dataset.create'))
                <a href="{{ route('datasets.create') }}" class="primary">
                    + Tambah Dataset
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="message">{{ session('success') }}</div>
        @endif

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Dataset</th>
                        <th>Divisi</th>
                        <th>Pengunggah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($datasets as $dataset)
                    <tr>
                        <td>{{ $dataset->name }}</td>
                        <td>{{ $dataset->department->name ?? '-' }}</td>
                        <td>{{ $dataset->uploader->name ?? '-' }}</td>
                        <td>{{ $dataset->status }}</td>
                        <td>
                            <div class="actions">
                                <a class="secondary"
                                   href="{{ route('datasets.show', $dataset) }}">
                                    Detail
                                </a>

                                @if (auth()->user()->role->permissions->contains('code', 'dataset.update'))
                                    <a class="secondary"
                                       href="{{ route('datasets.edit', $dataset) }}">
                                        Edit
                                    </a>
                                @endif

                                @if (auth()->user()->role->permissions->contains('code', 'dataset.delete'))
                                    <form method="POST"
                                          action="{{ route('datasets.destroy', $dataset) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus dataset ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="logout" type="submit">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">
                            Belum ada dataset. Tambahkan dataset pertama kamu.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px">
            {{ $datasets->links() }}
        </div>
    </section>
</main>
</body>
</html>