<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk | TanyAku</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<main class="login-page">
    <section class="login-aside">
        <a class="brand" href="{{ route('login') }}"><span class="brand-mark" aria-hidden="true">T</span><span><strong>TanyAku</strong><small>DATA WORKSPACE</small></span></a>
        <div class="login-message"><p class="eyebrow">DATA MANAGEMENT &amp; ANALYTICS</p><h1>Ubah data menjadi keputusan yang lebih baik.</h1><p>Kenali pola, temukan peluang, dan ambil langkah berikutnya bersama workspace datamu.</p></div>
        <footer>TanyAku <span>·</span> Data Management &amp; Analytics Platform</footer>
    </section>
    <section class="login-main">
        <div class="login-card">
            <p class="eyebrow">SELAMAT DATANG KEMBALI</p>
            <h2>Masuk ke TanyAku</h2>
            <p>Gunakan akun workspace kamu untuk melanjutkan.</p>
            @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
            @if ($errors->any())<div class="alert alert-error" role="alert">{{ $errors->first() }}</div>@endif
            <form class="form-grid" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-field"><label for="email">Email</label><input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@perusahaan.com" autocomplete="username" required autofocus></div>
                <div class="form-field"><label for="password">Kata Sandi</label><input type="password" id="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required></div>
                <button class="button button-primary" type="submit">Masuk <span aria-hidden="true">→</span></button>
            </form>
        </div>
    </section>
</main>
</body>
</html>
