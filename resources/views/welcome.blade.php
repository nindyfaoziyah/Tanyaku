<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TanyAku membantu tim Finance, HR, dan Marketing memahami data perusahaan dan mengambil keputusan lebih cerdas.">
    <title>TanyAku — Ubah Data Menjadi Keputusan yang Lebih Cerdas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="landing-page">
    <header class="landing-header">
        <a class="landing-brand" href="#beranda" aria-label="TanyAku, beranda"><img src="{{ asset('images/tanyaku-logo.png') }}" alt="TanyAku"></a>
        <nav class="landing-nav" aria-label="Navigasi utama"><a class="is-active" href="#beranda">Beranda</a><a href="#fitur">Fitur</a><a href="#solusi">Solusi</a><a href="#tentang">Tentang Kami</a></nav>
        <div class="landing-actions"><a class="landing-login" href="{{ route('login') }}">Masuk</a><a class="landing-button landing-button-small" href="{{ route('login') }}">Mulai Sekarang</a></div>
    </header>

    <main>
        <section class="landing-hero" id="beranda">
            <div class="landing-hero-copy reveal"><h1>Ubah Data Menjadi Keputusan yang <span>Lebih Cerdas.</span></h1><p>TanyAku membantu tim Finance, HR, dan Marketing memahami data perusahaan, menemukan insight tersembunyi, dan menentukan langkah strategis berikutnya dengan bantuan AI kontekstual.</p><div class="landing-hero-actions"><a class="landing-button" href="{{ route('login') }}">Mulai Sekarang <span aria-hidden="true">→</span></a><a class="landing-button landing-button-light" href="#fitur">Jelajahi Fitur <span aria-hidden="true">⊙</span></a></div><div class="landing-assurance"><span>♧</span> Satu platform terintegrasi. Analisis terisolasi dengan tata kelola data perusahaan.</div><div class="landing-stats"><div><strong>3 Divisi</strong><span>Finance, HR &amp; Growth</span></div><div><strong>100%</strong><span>Validasi Otomatis</span></div><div><strong>&lt; 3 Detik</strong><span>Generasi Insight AI</span></div></div></div>
            <div class="landing-dashboard reveal" aria-label="Contoh dashboard analitik TanyAku">
                <div class="dashboard-top"><span class="window-dots"><i></i><i></i><i></i></span><span>▱ &nbsp;Workspace &gt; <strong>Finance &amp; Marketing Q3</strong></span><span class="analysis-status">✓ Analisis Selesai</span></div>
                <div class="dashboard-metrics"><div><small>ROAS Rata-rata</small><strong>4.2× <em>↗ +18%</em></strong></div><div><small>Arus Kas Bersih</small><strong>24.8M <em>↗ +14%</em></strong></div><div><small>Skor KPI Karyawan</small><strong>86.4 <em>↗ +6.2%</em></strong></div></div>
                <div class="dashboard-chart"><div class="chart-heading"><div><strong>Tren Performa &amp; Realisasi Anggaran</strong><small>Mei – Oktober 2024 • Validasi otomatis</small></div><span><i></i> Marketing &nbsp; <b></b> Finance</span></div><div class="chart-bars" aria-label="Grafik performa Mei sampai Oktober"><div><i style="--bar:53%"></i><b style="--bar:41%"></b><small>Mei</small></div><div><i style="--bar:67%"></i><b style="--bar:53%"></b><small>Jun</small></div><div><i style="--bar:58%"></i><b style="--bar:51%"></b><small>Jul</small></div><div><i style="--bar:75%"></i><b style="--bar:63%"></b><small>Agu</small></div><div><i style="--bar:83%"></i><b style="--bar:79%"></b><small>Sep</small></div><div><i style="--bar:91%"></i><b style="--bar:85%"></b><small>Okt</small></div></div></div>
                <div class="insight-note"><span>✧</span><div><strong>Insight AI Terdeteksi</strong><p>1 temuan optimasi alokasi anggaran &amp; pertumbuhan efisiensi belanja iklan channel Q3 sebesar +18.4%.</p></div><small>Baru saja</small></div>
            </div>
        </section>

        <section class="landing-section features-section" id="fitur"><div class="section-intro reveal"><span class="section-pill">FITUR TERINTEGRASI</span><h2>Semua Insight dalam Satu Ruang Kerja</h2><p>TanyAku dirancang khusus untuk memenuhi kebutuhan analitis tiga pilar utama operasi bisnis perusahaan tanpa kompleksitas teknis.</p></div><div class="feature-grid">
            <article class="feature-card reveal"><span class="feature-icon finance-icon">▣</span><h3>Finance Analytics</h3><p>Bantu analisis data finansial, validasi saldo, dan temukan anomali transaksi yang membutuhkan perhatian cepat tim keuangan sebelum penutupan buku.</p><ul><li>Deteksi anomali selisih kas</li><li>Proyeksi burn-rate &amp; efisiensi operasional</li><li>Audit trail data masukan XLSX/CSV</li></ul><a href="{{ route('login') }}">Pelajari Fitur <span>→</span></a></article>
            <article class="feature-card reveal"><span class="feature-icon hr-icon">♧</span><h3>HR Analytics</h3><p>Pahami tren KPI karyawan, distribusi kinerja departemen, dan insight kompetensi yang mendukung pengambilan keputusan people strategy yang adil.</p><ul><li>Pelacakan kepatuhan skor KPI bulanan</li><li>Identifikasi penurunan performa dini</li><li>Rekomendasi kolaborasi lintas divisi</li></ul><a href="{{ route('login') }}">Pelajari Fitur <span>→</span></a></article>
            <article class="feature-card reveal"><span class="feature-icon marketing-icon">⚑</span><h3>Marketing Analytics</h3><p>Evaluasi performa multi-kampanye, efektivitas channel konversi, optimalisasi ROAS, dan kalkulasi biaya akuisisi pelanggan (CAC) secara real-time.</p><ul><li>Perbandingan efisiensi channel berbayar</li><li>Atribusi konversi kampanye terstruktur</li><li>Saran penyesuaian alokasi anggaran AI</li></ul><a href="{{ route('login') }}">Pelajari Fitur <span>→</span></a></article>
        </div></section>

        <section class="landing-section workflow-section" id="solusi"><div class="section-intro reveal"><span class="section-pill">ALUR KERJA SEDERHANA</span><h2>Dari Data Menjadi Insight</h2><p>Tiga langkah terstruktur mengubah berkas data operasional menjadi rekomendasi terarah tanpa perlu menulis kode kueri rumit.</p></div><div class="workflow-grid"><article class="workflow-card reveal"><span class="workflow-number">01</span><span class="workflow-icon">☁</span><h3>Hubungkan atau unggah data yang diizinkan</h3><p>Unggah berkas operasional berupa spreadsheet XLSX atau format CSV terstruktur. Data diisolasi penuh di tingkat ruang kerja tim Anda.</p><small>♙ Enkripsi TLS 1.3 &amp; AES-256</small></article><article class="workflow-card reveal"><span class="workflow-number">02</span><span class="workflow-icon">♧</span><h3>Analisis data dan temukan pola dengan bantuan AI</h3><p>Mesin analisis memvalidasi kelengkapan data, membersihkan anomali masukan, dan mengenali tren tersembunyi secara otomatis.</p><small>✣ Deteksi anomali instan</small></article><article class="workflow-card reveal"><span class="workflow-number">03</span><span class="workflow-icon">✓</span><h3>Gunakan insight untuk mendukung keputusan terarah</h3><p>Ekspor laporan PDF siap presentasi, diskusikan langkah strategis lewat AI Assistant, dan selaraskan eksekusi antardepartemen.</p><small>▧ Ekspor laporan eksekutif</small></article></div></section>

        <section class="landing-section standards-section" id="tentang"><div class="standards-heading reveal"><span class="section-pill">STANDAR ENTERPRISE</span><h2>Analisis yang Relevan dengan Ruang Kerja Anda</h2><p>Fondasi teknologi yang mengedepankan akurasi statistik, transparansi model komputasi, dan tata kelola data perusahaan yang bertanggung jawab.</p></div><div class="standards-grid"><article class="standard-card reveal"><span>✿</span><h3>AI-Powered Insights</h3><p>Membantu tim memahami pola dan temuan dalam data secara kontekstual tanpa spekulasi. Setiap kesimpulan dilengkapi referensi baris data yang diverifikasi.</p></article><article class="standard-card reveal"><span>☷</span><h3>Data Validation</h3><p>Menemukan sel kosong, record duplikat, dan potensi anomali format kualitas data sebelum dianalisis, menjamin validitas keputusan akhir.</p></article><article class="standard-card reveal"><span>♧</span><h3>Department Data Isolation</h3><p>Hak akses dan repositori data setiap departemen dipartisi ketat. Akses hanya diberikan kepada personil yang terotorisasi dalam role perusahaan.</p></article></div></section>

        <section class="landing-cta-wrap"><div class="landing-cta reveal"><span class="section-pill">ϟ &nbsp; Implementasi Cepat &amp; Terintegrasi</span><h2>Siap Memahami Data Anda Lebih<br> Dalam?</h2><p>Mulai eksplorasi insight yang membantu tim Finance, HR, dan Marketing Anda mengambil keputusan operasional dengan presisi lebih tinggi.</p><div><a class="landing-button" href="{{ route('login') }}">Mulai Sekarang <span>→</span></a><a class="landing-button landing-button-light" href="mailto:halo@tanyaku.id">Konsultasi Enterprise</a></div><small>Tidak memerlukan instalasi rumit • Akses fleksibel via browser desktop</small></div></section>
    </main>

    <footer class="landing-footer"><div class="footer-main"><div><a class="landing-brand" href="#beranda" aria-label="TanyAku, beranda"><img src="{{ asset('images/tanyaku-logo.png') }}" alt="TanyAku"></a><p>Platform decision intelligence dan analitik bisnis berbasis AI untuk menyederhanakan data perusahaan Finance, HR, dan Marketing secara aman dan presisi.</p></div><div><h3>Solusi Divisi</h3><a href="#fitur">Finance Analytics</a><a href="#fitur">People &amp; HR Intelligence</a><a href="#fitur">Marketing &amp; Growth</a></div><div><h3>Platform</h3><a href="#solusi">AI Assistant</a><a href="#tentang">Isolasi Data Perusahaan</a><a href="#solusi">Validasi Otomatis</a></div><div><h3>Perusahaan</h3><a href="#tentang">Tentang Kami</a><a href="#tentang">Keamanan &amp; Privasi</a><a href="mailto:halo@tanyaku.id">Hubungi Kami</a></div></div><div class="footer-bottom"><span>© {{ date('Y') }} TanyAku Enterprise. Seluruh hak cipta dilindungi.</span><div><a href="#tentang">Kebijakan Privasi</a><a href="#tentang">Syarat Layanan</a><a href="#tentang">Status Sistem</a></div></div></footer>
    <script>
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.14 });
        document.querySelectorAll('.reveal').forEach((element) => revealObserver.observe(element));
    </script>
</body>
</html>
