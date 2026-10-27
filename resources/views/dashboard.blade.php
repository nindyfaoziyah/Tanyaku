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
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #202437;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 20px 6%;
            background: white;
            border-bottom: 1px solid #e8eaf2;
        }

        header h1 {
            margin: 0;
            color: #5146d8;
            font-size: 24px;
        }

        .logout-button {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            background: #5146d8;
            color: white;
            cursor: pointer;
        }

        main {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 24px;
        }

        .welcome {
            padding: 30px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.04);
        }

        .welcome h2 {
            margin-top: 0;
        }

        .status {
            display: inline-block;
            margin-top: 12px;
            padding: 8px 12px;
            border-radius: 20px;
            background: #e7f8ee;
            color: #187443;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <header>
        <h1>TanyAku</h1>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-button" type="submit">
                Logout
            </button>
        </form>
    </header>

    <main>
        <section class="welcome">
            <h2>Selamat datang, {{ auth()->user()->name }}!</h2>

            <p>
                Kamu berhasil login ke platform TanyAku.
            </p>

            <p>
                Email: {{ auth()->user()->email }}
            </p>

            <span class="status">Login berhasil</span>

            <div style="margin-top: 24px;">
    @if (auth()->user()->role->permissions->contains('code', 'dataset.view'))
        <a
            href="{{ route('datasets.index') }}"
            style="
                display: inline-block;
                padding: 12px 18px;
                background: #5146d8;
                color: white;
                border-radius: 8px;
                text-decoration: none;
            "
        >
            Kelola Dataset
        </a>
    @endif
</div>
        </section>
    </main>
</body>
</html>