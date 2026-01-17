{{-- File: resources/views/landing/tracking.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lacak Pengiriman - {{ $settings['company_name'] ?? 'Eureka Kopi' }}</title>
    <meta name="description" content="Lacak pengiriman paket Anda melalui JNE, J&T, SiCepat, dan ekspedisi lainnya">

    <!-- Favicon -->
    @if (!empty($settings['company_logo']))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $settings['company_logo']) }}" />
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4a3728;
            --secondary-color: #8b6f4e;
            --accent-color: #c9a66b;
            --dark-color: #2d1f14;
            --light-color: #f8f5f0;
            --cream-color: #faf6f1;
            --text-color: #333333;
            --text-light: #666666;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--cream-color) 0%, var(--light-color) 100%);
            min-height: 100vh;
            color: var(--text-color);
        }

        /* Header */
        .tracking-header {
            background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%);
            padding: 2rem 0;
            color: #fff;
            text-align: center;
        }

        .tracking-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .tracking-header p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .back-btn {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            color: var(--accent-color);
        }

        /* Main Content */
        .tracking-content {
            padding: 3rem 0;
        }

        .tracking-intro {
            text-align: center;
            margin-bottom: 3rem;
        }

        .tracking-intro h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .tracking-intro p {
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Courier Cards */
        .courier-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .courier-card {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            border: 2px solid transparent;
        }

        .courier-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
            border-color: var(--accent-color);
        }

        .courier-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-color);
            border-radius: 50%;
            overflow: hidden;
        }

        .courier-logo img {
            max-width: 70%;
            max-height: 70%;
            object-fit: contain;
        }

        .courier-logo i {
            font-size: 3rem;
            color: var(--secondary-color);
        }

        .courier-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .courier-card p {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        .btn-track {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-track:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(201, 166, 107, 0.4);
            color: #fff;
        }

        /* JNE Highlight */
        .courier-card.jne-highlight {
            border: 2px solid var(--accent-color);
            position: relative;
            overflow: hidden;
        }

        .courier-card.jne-highlight::before {
            content: 'RECOMMENDED';
            position: absolute;
            top: 15px;
            right: -30px;
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: #fff;
            padding: 5px 40px;
            font-size: 0.7rem;
            font-weight: 700;
            transform: rotate(45deg);
        }

        /* Info Section */
        .info-section {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .info-section h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-section h3 i {
            color: var(--accent-color);
        }

        .info-section ul {
            list-style: none;
            padding: 0;
        }

        .info-section ul li {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--light-color);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .info-section ul li:last-child {
            border-bottom: none;
        }

        .info-section ul li i {
            color: var(--accent-color);
            font-size: 1.25rem;
            margin-top: 2px;
        }

        /* Footer */
        .tracking-footer {
            background: var(--dark-color);
            color: rgba(255, 255, 255, 0.7);
            padding: 1.5rem 0;
            text-align: center;
        }

        .tracking-footer a {
            color: var(--accent-color);
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .tracking-header h1 {
                font-size: 1.5rem;
            }

            .back-btn {
                position: static;
                margin-bottom: 1rem;
                justify-content: center;
            }

            .courier-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="tracking-header">
        <div class="container position-relative">
            <a href="{{ route('landing') }}" class="back-btn">
                <i class='bx bx-arrow-back'></i>
                Kembali
            </a>
            <h1><i class='bx bx-package me-2'></i>Lacak Pengiriman</h1>
            <p>Pantau status pengiriman pesanan Anda dengan mudah</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="tracking-content">
        <div class="container">
            <!-- Intro -->
            <div class="tracking-intro">
                <h2>Pilih Ekspedisi untuk Melacak Paket Anda</h2>
                <p>Klik pada layanan ekspedisi yang Anda gunakan untuk langsung melacak status pengiriman paket Anda</p>
            </div>

            <!-- Courier Cards -->
            <div class="courier-grid">
                <!-- JNE -->
                <div class="courier-card jne-highlight">
                    <div class="courier-logo">
                        <i class='bx bxs-truck'></i>
                    </div>
                    <h3>JNE Express</h3>
                    <p>Jalur Nugraha Ekakurir - Layanan pengiriman terpercaya di Indonesia</p>
                    <a href="https://www.jne.co.id/id/tracking/trace" target="_blank" class="btn-track">
                        <i class='bx bx-search-alt'></i>
                        Lacak di JNE
                    </a>
                </div>

                <!-- J&T -->
                <div class="courier-card">
                    <div class="courier-logo">
                        <i class='bx bxs-package'></i>
                    </div>
                    <h3>J&T Express</h3>
                    <p>Express your online business - Pengiriman cepat dan aman</p>
                    <a href="https://www.jet.co.id/track" target="_blank" class="btn-track">
                        <i class='bx bx-search-alt'></i>
                        Lacak di J&T
                    </a>
                </div>

                <!-- SiCepat -->
                <div class="courier-card">
                    <div class="courier-logo">
                        <i class='bx bxs-plane-take-off'></i>
                    </div>
                    <h3>SiCepat Ekspres</h3>
                    <p>Pengiriman kilat dengan harga ekonomis</p>
                    <a href="https://www.sicepat.com/checkAwb" target="_blank" class="btn-track">
                        <i class='bx bx-search-alt'></i>
                        Lacak di SiCepat
                    </a>
                </div>

                <!-- Anteraja -->
                <div class="courier-card">
                    <div class="courier-logo">
                        <i class='bx bxs-paper-plane'></i>
                    </div>
                    <h3>AnterAja</h3>
                    <p>Solusi pengiriman modern untuk kebutuhan Anda</p>
                    <a href="https://anteraja.id/tracking" target="_blank" class="btn-track">
                        <i class='bx bx-search-alt'></i>
                        Lacak di AnterAja
                    </a>
                </div>

                <!-- POS Indonesia -->
                <div class="courier-card">
                    <div class="courier-logo">
                        <i class='bx bxs-envelope'></i>
                    </div>
                    <h3>POS Indonesia</h3>
                    <p>Layanan pos terpercaya sejak 1746</p>
                    <a href="https://www.posindonesia.co.id/id/tracking" target="_blank" class="btn-track">
                        <i class='bx bx-search-alt'></i>
                        Lacak di POS
                    </a>
                </div>

                <!-- Ninja Express -->
                <div class="courier-card">
                    <div class="courier-logo">
                        <i class='bx bxs-bolt'></i>
                    </div>
                    <h3>Ninja Xpress</h3>
                    <p>Tech-enabled express logistics company</p>
                    <a href="https://www.ninjaxpress.co/id-id/tracking" target="_blank" class="btn-track">
                        <i class='bx bx-search-alt'></i>
                        Lacak di Ninja
                    </a>
                </div>
            </div>

            <!-- Info Section -->
            <div class="info-section">
                <h3><i class='bx bx-info-circle'></i>Cara Melacak Pengiriman</h3>
                <ul>
                    <li>
                        <i class='bx bx-check-circle'></i>
                        <div>
                            <strong>Siapkan Nomor Resi</strong><br>
                            <small class="text-muted">Nomor resi/AWB dapat ditemukan di invoice pembelian atau
                                konfirmasi pengiriman via email/WhatsApp</small>
                        </div>
                    </li>
                    <li>
                        <i class='bx bx-check-circle'></i>
                        <div>
                            <strong>Pilih Ekspedisi yang Tepat</strong><br>
                            <small class="text-muted">Pastikan memilih ekspedisi yang sesuai dengan kurir pengirim paket
                                Anda</small>
                        </div>
                    </li>
                    <li>
                        <i class='bx bx-check-circle'></i>
                        <div>
                            <strong>Masukkan Nomor Resi</strong><br>
                            <small class="text-muted">Input nomor resi pada kolom tracking di website ekspedisi yang
                                dipilih</small>
                        </div>
                    </li>
                    <li>
                        <i class='bx bx-check-circle'></i>
                        <div>
                            <strong>Pantau Status Pengiriman</strong><br>
                            <small class="text-muted">Anda dapat melihat detail perjalanan paket dari titik awal hingga
                                tujuan</small>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Contact Support -->
            <div class="text-center mt-4">
                <p class="text-muted mb-2">Ada kendala dengan pengiriman?</p>
                @if (!empty($settings['landing_whatsapp']))
                    <a href="https://wa.me/{{ $settings['landing_whatsapp'] }}?text={{ urlencode('Halo, saya ingin menanyakan status pengiriman pesanan saya') }}"
                        class="btn-track" target="_blank">
                        <i class='bx bxl-whatsapp'></i>
                        Hubungi Customer Service
                    </a>
                @endif
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="tracking-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} {{ $settings['company_name'] ?? 'Eureka Kopi' }}.
                <a href="{{ route('landing') }}">Kembali ke Beranda</a>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
