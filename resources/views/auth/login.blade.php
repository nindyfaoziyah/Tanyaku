<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Masuk ke ruang kerja analitik TanyAku.">
    <title>Masuk | TanyAku</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-form-wrap > .login-failure-alert { display: flex; align-items: flex-start; gap: 12px; margin: -8px 0 25px; padding: 15px 17px; border: 1px solid #ffb9b9; border-radius: 19px; background: #fff2f2; color: #c92323; }
        .login-failure-alert .failure-icon { display: grid; width: 20px; height: 20px; flex: 0 0 auto; place-items: center; border: 2px solid currentColor; border-radius: 50%; font-size: 13px; font-weight: 700; line-height: 1; }
        .login-failure-alert strong { display: block; font-size: 17px; line-height: 1.3; }
        .login-failure-alert p { margin: 7px 0 0; font-size: 15px; line-height: 1.4; }
        .login-failure-alert .alert-dismiss { display: grid; width: 28px; height: 28px; flex: 0 0 auto; place-items: center; margin: -3px -3px 0 auto; padding: 0; border: 0; background: transparent; color: #f04455; cursor: pointer; }
        .login-failure-alert .close-icon { position: relative; width: 20px; height: 20px; }
        .login-failure-alert .close-icon::before, .login-failure-alert .close-icon::after { position: absolute; top: 9px; left: 2px; width: 16px; height: 2px; border-radius: 2px; background: currentColor; content: ""; }
        .login-failure-alert .close-icon::before { transform: rotate(45deg); }
        .login-failure-alert .close-icon::after { transform: rotate(-45deg); }
        .report-toolbar { display: flex; width: 100%; align-items: center; justify-content: flex-end; flex-wrap: nowrap; gap: 8px; min-width: 0; }
        .report-toolbar > span { display: inline-flex; min-width: 0; flex: 0 1 auto; align-items: center; justify-content: center; gap: 4px; white-space: nowrap; }
        .report-toolbar svg { display: block; width: 14px; height: 14px; flex: 0 0 14px; margin: 0; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .report-growth { display: inline-flex; width: fit-content; max-width: 100%; align-items: center; gap: 4px; white-space: nowrap; vertical-align: middle; }
        .report-growth svg { display: block; width: 13px; height: 13px; flex: 0 0 13px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .report-stats > div { min-width: 0; overflow: hidden; }
        .report-attention { display: flex; flex-wrap: nowrap; align-items: center; gap: 4px; white-space: nowrap; line-height: 1.4; }
        .report-attention b { display: inline-flex; max-width: 100%; flex: 0 0 auto; align-items: center; gap: 2px; white-space: nowrap; line-height: 1.2; }
        .report-attention b svg { width: 13px; height: 13px; flex: 0 0 13px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .auth-legal i { display: inline-block; width: 4px; height: 4px; margin: 0 13px; border-radius: 50%; background: currentColor; vertical-align: middle; }
        .auth-input-wrap.is-invalid { border-color: #ff4545; box-shadow: 0 0 0 1px #ff45451c; }
        .auth-input-wrap.is-valid { border-color: #cbd7e6; }
        .auth-input-wrap.is-valid > svg { stroke: #172033; }
        .field-status { position: relative; display: none; width: 26px; height: 26px; flex: 0 0 auto; place-items: center; border-radius: 50%; }
        .auth-input-wrap.is-invalid .field-status, .auth-input-wrap.is-valid .field-status { display: grid; }
        .auth-input-wrap.is-invalid .field-status { background: #ff4545; }
        .auth-input-wrap.is-valid .field-status { background: #10b981; }
        .auth-input-wrap.is-invalid .field-status::before, .auth-input-wrap.is-invalid .field-status::after { position: absolute; width: 13px; height: 2px; border-radius: 2px; background: #fff; content: ''; }
        .auth-input-wrap.is-invalid .field-status::before { transform: rotate(45deg); }
        .auth-input-wrap.is-invalid .field-status::after { transform: rotate(-45deg); }
        .auth-input-wrap.is-valid .field-status::before { width: 7px; height: 12px; border-right: 2px solid #fff; border-bottom: 2px solid #fff; content: ''; transform: translateY(-1px) rotate(45deg); }
        .field-error { display: flex; align-items: center; gap: 8px; margin: 7px 0 0; color: #ff4545; font-size: 14px; line-height: 1.4; }
        .field-error-icon { position: relative; display: block; width: 19px; height: 19px; flex: 0 0 auto; border: 2px solid currentColor; border-radius: 50%; }
        .field-error-icon::before, .field-error-icon::after { position: absolute; left: 7px; background: currentColor; content: ''; }
        .field-error-icon::before { top: 3px; width: 2px; height: 6px; }
        .field-error-icon::after { top: 11px; width: 2px; height: 2px; border-radius: 50%; }
        .field-error-message { display: block; min-width: 0; }
        .field-error > .field-error-message { width: auto; height: auto; flex: 1 1 auto; place-items: initial; border: 0; border-radius: 0; font-size: inherit; font-weight: inherit; line-height: inherit; }
        .auth-field .auth-input-wrap.is-invalid:focus-within { border-color: #ff4545; box-shadow: 0 0 0 3px #ff45451c; }
        @media (max-width: 640px) { .auth-form-wrap > .login-failure-alert { margin: -4px 0 19px; padding: 12px 13px; border-radius: 15px; }.login-failure-alert p { font-size: 13px; } }
    </style>
</head>
<body class="auth-screen">
    <main class="auth-shell">
        <section class="auth-visual" aria-label="Pratinjau analitik TanyAku">
            <div class="auth-pagination" aria-hidden="true"><i></i><i></i><i></i></div>

            <div class="report-preview">
                <div class="report-toolbar"><span>Jul 1, 2025 &ndash; Jul 30, 2025 &nbsp;<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg></span><span>Unduh Laporan PDF &nbsp;<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12m0 0 4-4m-4 4-4-4M5 17v3h14v-3"/></svg></span></div>
                <div class="report-stats">
                    <div><small>CUSTOMER RETENTION</small><strong>94,2%</strong><span class="report-growth"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 14 11 8l4 4 5-7M14 5h6v6"/></svg> +12,76%</span></div>
                    <div><small>Needs Attention</small><strong>3</strong><span class="report-attention">Anomali Terdeteksi &nbsp; <b>Lihat Detail <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg></b></span></div>
                </div>
                <div class="report-chart-title">Rata-rata Konversi Real-Time</div>
                <div class="report-chart">
                    <svg viewBox="0 0 540 190" role="img" aria-label="Grafik tren konversi meningkat">
                        <defs><linearGradient id="chart-fill" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#06B6D4" stop-opacity=".2"/><stop offset="1" stop-color="#06B6D4" stop-opacity="0"/></linearGradient></defs>
                        <path class="chart-gridline" d="M0 35H540M0 83H540M0 131H540M0 178H540"/>
                        <path d="M0 132 C70 148 120 89 190 46 S293 68 354 100 S440 64 493 59 S524 53 540 48 L540 180 L0 180Z" fill="url(#chart-fill)"/>
                        <path d="M0 132 C70 148 120 89 190 46 S293 68 354 100 S440 64 493 59 S524 53 540 48" fill="none" stroke="#06B6D4" stroke-width="3.5"/>
                        <path d="M0 145 C76 162 128 102 197 55 S302 79 361 107 S442 86 498 81 S523 80 540 77" fill="none" stroke="#2452BB" stroke-width="3"/>
                        <path d="M0 163 C65 175 104 145 153 135 S259 152 337 161 S445 140 491 116 L540 119" fill="none" stroke="#B6D3E2" stroke-width="2.5"/>
                        <path d="M197 55V180" stroke="#96B6D6" stroke-dasharray="4 5"/><circle cx="197" cy="55" r="7" fill="#fff" stroke="#2452BB" stroke-width="3"/>
                    </svg>
                    <span class="chart-tooltip">9 Jul 2025 &nbsp; <strong>Rp 328.450.000</strong></span>
                    <div class="chart-days"><span>06</span><span>07</span><span>08</span><span>09</span><span>10</span><span>11</span><span>12</span><span>13</span><span>14</span><span>15</span></div>
                </div>
                <div class="chart-legend"><span><i></i> Bulan Ini (Aktif)</span><span><i></i> Bulan Lalu</span></div>
            </div>
        </section>

        <section class="auth-content">
            <div class="auth-form-wrap">
                <h1>Masuk ke <span>TanyAku</span></h1>
                <p class="auth-subtitle">Gunakan email perusahaan untuk melanjutkan.</p>
                @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
                @if (session('login_failure'))
                    <div class="login-failure-alert" role="alert">
                        <span class="failure-icon" aria-hidden="true">!</span>
                        <div><strong>Gagal masuk</strong><p>Email atau kata sandi tidak sesuai. Silakan periksa kembali.</p></div>
                        <button type="button" class="alert-dismiss" aria-label="Tutup pemberitahuan"><span class="close-icon" aria-hidden="true"></span></button>
                    </div>
                @endif
                <form class="auth-form" method="POST" action="{{ route('login') }}" autocomplete="off">
                    @csrf
                    <div class="auth-field">
                        <label for="email">Email Perusahaan</label>
                        <div class="auth-input-wrap @error('email') is-invalid @else @if(old('email')) is-valid @endif @enderror">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@perusahaan.com" autocomplete="username" data-check-url="{{ route('login.email-check') }}" aria-invalid="@error('email') true @else false @enderror" aria-describedby="email-error" required autofocus>
                            <span class="field-status" aria-hidden="true"></span>
                        </div>
                        @error('email')<p class="field-error" id="email-error" role="alert"><span class="field-error-icon" aria-hidden="true"></span><span class="field-error-message">{{ $message }}</span></p>@enderror
                    </div>
                    <div class="auth-field">
                        <label for="password">Kata Sandi</label>
                        <div class="auth-input-wrap @error('password') is-invalid @enderror">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                            <input type="password" id="password" name="password" placeholder="Masukan Kata sandi" autocomplete="off" aria-invalid="@error('password') true @else false @enderror" aria-describedby="password-error" required>
                            <button class="password-toggle" type="button" aria-label="Tampilkan kata sandi" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/><circle cx="12" cy="12" r="3"/></svg></button>
                        </div>
                        @error('password')<p class="field-error" id="password-error" role="alert"><span class="field-error-icon" aria-hidden="true"></span><span class="field-error-message">{{ $message }}</span></p>@enderror
                    </div>
                    <div class="auth-options"><label><input type="checkbox" name="remember" value="1"> <span>Ingat saya</span></label><a href="mailto:halo@tanyaku.id?subject=Lupa%20Kata%20Sandi">Lupa Kata Sandi?</a></div>
                    <button class="auth-submit" type="submit">Masuk</button>
                </form>
            </div>
            <footer class="auth-legal"><a href="#privasi">Kebijakan Privasi</a><i aria-hidden="true"></i><a href="#layanan">Ketentuan Layanan</a><i aria-hidden="true"></i><a href="mailto:halo@tanyaku.id">Pusat Bantuan</a></footer>
        </section>
    </main>
    <script>
        const passwordToggle = document.querySelector('.password-toggle');
        const passwordInput = document.querySelector('#password');
        passwordToggle.addEventListener('click', () => {
            const isPasswordHidden = passwordInput.type === 'password';
            passwordInput.type = isPasswordHidden ? 'text' : 'password';
            passwordToggle.setAttribute('aria-pressed', String(isPasswordHidden));
            passwordToggle.setAttribute('aria-label', isPasswordHidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        });

        document.querySelector('.alert-dismiss')?.addEventListener('click', (event) => {
            event.currentTarget.closest('.login-failure-alert').remove();
        });

        const emailInput = document.querySelector('#email');
        const emailField = emailInput.closest('.auth-field');
        const emailWrap = emailInput.closest('.auth-input-wrap');
        const emailStatus = emailWrap.querySelector('.field-status');
        let emailCheckTimer;
        let emailCheckController;
        let emailCheckSequence = 0;

        const setEmailState = (state, message = '') => {
            emailWrap.classList.remove('is-invalid', 'is-valid');
            emailInput.setAttribute('aria-invalid', state === 'invalid' ? 'true' : 'false');
            emailStatus.textContent = '';
            if (state === 'invalid') {
                emailWrap.classList.add('is-invalid');
            } else if (state === 'valid') {
                emailWrap.classList.add('is-valid');
            }

            let inlineError = emailField.querySelector('.field-error');
            if (message) {
                if (!inlineError) {
                    inlineError = document.createElement('p');
                    inlineError.className = 'field-error';
                    inlineError.id = 'email-error';
                    inlineError.setAttribute('role', 'alert');
                    const icon = document.createElement('span');
                    icon.className = 'field-error-icon';
                    icon.setAttribute('aria-hidden', 'true');
                    inlineError.append(icon);
                    const errorMessage = document.createElement('span');
                    errorMessage.className = 'field-error-message';
                    inlineError.append(errorMessage);
                    emailField.append(inlineError);
                }
                inlineError.querySelector('.field-error-message').textContent = message;
                emailInput.setAttribute('aria-describedby', 'email-error');
            } else if (inlineError) {
                inlineError.remove();
                emailInput.removeAttribute('aria-describedby');
            }
        };

        emailInput.addEventListener('input', () => {
            const sequence = ++emailCheckSequence;
            window.clearTimeout(emailCheckTimer);
            emailCheckController?.abort();
            setEmailState('pending');

            if (!emailInput.value) {
                return;
            }

            if (!emailInput.validity.valid) {
                if (emailInput.value.includes('@')) {
                    setEmailState('invalid', 'Format email belum valid. Periksa kembali.');
                }
                return;
            }

            emailCheckTimer = window.setTimeout(async () => {
                emailCheckController = new AbortController();

                try {
                    const checkUrl = new URL(emailInput.dataset.checkUrl, window.location.origin);
                    checkUrl.searchParams.set('email', emailInput.value.trim());
                    const response = await fetch(checkUrl, {
                        headers: { Accept: 'application/json' },
                        signal: emailCheckController.signal,
                    });

                    if (sequence !== emailCheckSequence) {
                        return;
                    }

                    if (!response.ok) {
                        setEmailState('invalid', 'Email belum dapat diperiksa. Coba kembali.');
                        return;
                    }

                    const result = await response.json();
                    if (sequence !== emailCheckSequence) {
                        return;
                    }

                    if (result.registered) {
                        setEmailState('valid');
                    } else if (result.valid) {
                        setEmailState('invalid', 'Email belum terdaftar. Periksa kembali.');
                    } else {
                        setEmailState('invalid', 'Format email belum valid. Periksa kembali.');
                    }
                } catch (error) {
                    if (error.name !== 'AbortError' && sequence === emailCheckSequence) {
                        setEmailState('invalid', 'Email belum dapat diperiksa. Coba kembali.');
                    }
                }
            }, 400);
        });
    </script>
</body>
</html>
