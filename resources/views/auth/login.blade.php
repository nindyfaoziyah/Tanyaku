<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | TanyAku</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #202437;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 36px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
        }

        .brand {
            margin-bottom: 28px;
            text-align: center;
        }

        .brand h1 {
            margin: 0 0 8px;
            color: #5146d8;
            font-size: 32px;
        }

        .brand p {
            margin: 0;
            color: #687086;
            line-height: 1.5;
        }

        label {
            display: block;
            margin: 18px 0 8px;
            font-size: 14px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d8dbea;
            border-radius: 8px;
            font-size: 15px;
        }

        input:focus {
            outline: 2px solid #d8d4ff;
            border-color: #5146d8;
        }

        button {
            width: 100%;
            margin-top: 24px;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #5146d8;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #4035bd;
        }

        .message {
            margin-bottom: 16px;
            padding: 12px;
            border-radius: 8px;
            background: #fff0f0;
            color: #a12626;
            font-size: 14px;
            line-height: 1.5;
        }

        .success {
            background: #ecfdf3;
            color: #187443;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            color: #858ba0;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <main class="login-card">
        <div class="brand">
            <h1>TanyAku</h1>
            <p>Data Management &amp; Analytics Platform</p>
        </div>

        @if (session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="message">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email"
                autocomplete="username"
                required
                autofocus
            >

            <label for="password">Kata Sandi</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan kata sandi"
                autocomplete="current-password"
                required
            >

            <button type="submit">Masuk</button>
        </form>

        <div class="footer">
            TanyAku &copy; {{ date('Y') }}
        </div>
    </main>
</body>
</html>