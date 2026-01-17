{{-- File: resources/views/landing/index.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings['company_name'] ?? 'Eureka Kopi' }} - Supplier Kopi Terbaik Indonesia</title>
    <meta name="description" content="{{ $settings['landing_hero_subtitle'] ?? 'Supplier kopi berkualitas premium' }}">

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

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
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

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            overflow-x: hidden;
        }

        /* =============================================
           NAVBAR STYLES
        ============================================= */
        .navbar-landing {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.3s ease;
            background: rgba(45, 31, 20, 0.85);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
        }

        .navbar-landing.scrolled {
            background: rgba(45, 31, 20, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
            padding: 0.5rem 0;
        }

        .navbar-landing .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-landing .navbar-brand img {
            height: 45px;
            width: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent-color);
        }

        .navbar-landing .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-landing .nav-link:hover,
        .navbar-landing .nav-link.active {
            color: var(--accent-color) !important;
        }

        .navbar-landing .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar-landing .nav-link:hover::after,
        .navbar-landing .nav-link.active::after {
            width: 60%;
        }

        .navbar-landing .btn-login {
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            color: #fff !important;
            border: none;
            padding: 0.5rem 1.5rem !important;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .navbar-landing .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(201, 166, 107, 0.4);
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* =============================================
           HERO SECTION / CAROUSEL
        ============================================= */
        .hero-section {
            position: relative;
            height: 100vh;
            min-height: 600px;
        }

        .hero-swiper {
            width: 100%;
            height: 100%;
        }

        .hero-slide {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-slide-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .hero-slide-bg.no-image {
            background: linear-gradient(135deg, #2d1f14 0%, #4a3728 50%, #6b4f3a 100%);
        }

        .hero-slide-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(45, 31, 20, 0.85) 0%, rgba(74, 55, 40, 0.7) 100%);
        }

        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            color: #fff;
            max-width: 800px;
            padding: 0 2rem;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.8;
        }

        .hero-btn {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
        }

        .hero-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(201, 166, 107, 0.4);
            color: #fff;
        }

        /* Swiper Navigation */
        .hero-swiper .swiper-button-next,
        .hero-swiper .swiper-button-prev {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .hero-swiper .swiper-button-next:hover,
        .hero-swiper .swiper-button-prev:hover {
            background: var(--accent-color);
        }

        .hero-swiper .swiper-button-next::after,
        .hero-swiper .swiper-button-prev::after {
            font-size: 1.2rem;
        }

        .hero-swiper .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 1;
        }

        .hero-swiper .swiper-pagination-bullet-active {
            background: var(--accent-color);
        }

        /* =============================================
           SECTION STYLES
        ============================================= */
        section {
            padding: 100px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            border-radius: 2px;
        }

        .section-title p {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 1.5rem auto 0;
        }

        /* =============================================
           PRODUCTS SECTION
        ============================================= */
        #produk {
            background: var(--cream-color);
        }

        .category-filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .category-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--secondary-color);
            background: transparent;
            color: var(--secondary-color);
            border-radius: 50px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-btn:hover,
        .category-btn.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }

        .product-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            position: relative;
            height: 220px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--accent-color);
            color: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-info h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .product-info .price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .product-info .unit {
            font-size: 0.875rem;
            color: var(--text-light);
            font-weight: 400;
        }

        .btn-detail {
            display: block;
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-detail:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            color: #fff;
        }

        /* Products Swiper */
        .products-swiper {
            padding: 20px 0 60px !important;
        }

        .products-swiper .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
            background: var(--secondary-color);
        }

        .products-swiper .swiper-pagination-bullet-active {
            background: var(--accent-color);
            width: 30px;
            border-radius: 5px;
        }

        /* =============================================
           ABOUT SECTION
        ============================================= */
        #tentang {
            background: #fff;
        }

        .about-content {
            display: flex;
            align-items: center;
            gap: 4rem;
        }

        .about-image {
            flex: 1;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        }

        .about-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .about-text {
            flex: 1;
        }

        .about-text h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            position: relative;
        }

        .about-text h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            border-radius: 2px;
        }

        .about-text p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-light);
            margin-top: 2rem;
        }

        .about-features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .about-feature {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .about-feature i {
            font-size: 2rem;
            color: var(--accent-color);
        }

        .about-feature span {
            font-weight: 500;
            color: var(--primary-color);
        }

        /* =============================================
           PROMO SECTION
        ============================================= */
        #promo {
            background: linear-gradient(135deg, #fff5f5 0%, #fef3cd 100%);
            position: relative;
            overflow: hidden;
        }

        #promo::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(201, 166, 107, 0.1);
            border-radius: 50%;
        }

        #promo::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 150px;
            height: 150px;
            background: rgba(220, 53, 69, 0.1);
            border-radius: 50%;
        }

        .promo-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            height: 100%;
            position: relative;
        }

        .promo-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #dc3545, #ffc107, #28a745);
        }

        .promo-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .promo-image {
            width: 100%;
            height: 180px;
            overflow: hidden;
            position: relative;
        }

        .promo-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .promo-card:hover .promo-image img {
            transform: scale(1.1);
        }

        .promo-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: #fff;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .promo-content {
            padding: 1.5rem;
            text-align: center;
        }

        .promo-card h4 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.75rem;
        }

        .promo-card p {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .promo-code {
            display: inline-block;
            background: linear-gradient(135deg, #ffc107, #ffdb4d);
            color: var(--dark-color);
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 1px;
            border: 2px dashed var(--primary-color);
        }

        .promo-validity {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .promo-validity i {
            color: var(--accent-color);
        }

        /* Promo Swiper */
        .promo-swiper {
            padding: 20px 0 60px !important;
        }

        /* =============================================
           CONTACT SECTION
        ============================================= */
        #kontak {
            background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%);
            color: #fff;
        }

        #kontak .section-title h2 {
            color: #fff;
        }

        #kontak .section-title h2::after {
            background: var(--accent-color);
        }

        #kontak .section-title p {
            color: rgba(255, 255, 255, 0.8);
        }

        .contact-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .contact-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .contact-card:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-5px);
        }

        .contact-card i {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .contact-card h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .contact-card p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
        }

        .contact-card a {
            color: var(--accent-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-card a:hover {
            color: #fff;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 3rem;
        }

        .social-link {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-link:hover {
            background: var(--accent-color);
            color: #fff;
            transform: translateY(-3px);
        }

        /* =============================================
           FOOTER
        ============================================= */
        footer {
            background: var(--dark-color);
            color: rgba(255, 255, 255, 0.7);
            padding: 2rem 0;
            text-align: center;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        footer a {
            color: var(--accent-color);
            text-decoration: none;
        }

        /* =============================================
           WHATSAPP FLOATING BUTTON
        ============================================= */
        .whatsapp-float-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 999;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .whatsapp-float-label {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: #fff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
            animation: bounce 2s infinite;
            white-space: nowrap;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-8px);
            }

            60% {
                transform: translateY(-4px);
            }
        }

        .whatsapp-float {
            width: 60px;
            height: 60px;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2rem;
            box-shadow: 0 5px 20px rgba(37, 211, 102, 0.4);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            color: #fff;
            box-shadow: 0 8px 30px rgba(37, 211, 102, 0.6);
        }

        /* =============================================
           PRODUCT DETAIL MODAL
        ============================================= */
        .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }

        .modal-header {
            background: var(--primary-color);
            color: #fff;
            border: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .product-detail-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 15px;
        }

        .product-detail-info h4 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .product-detail-price {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--accent-color);
        }

        .product-detail-table td {
            padding: 0.5rem 0;
        }

        .product-detail-table td:first-child {
            font-weight: 500;
            color: var(--text-light);
            width: 40%;
        }

        /* =============================================
           RESPONSIVE
        ============================================= */
        @media (max-width: 991px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .about-content {
                flex-direction: column;
            }

            .about-image {
                order: -1;
            }

            .about-text h2 {
                font-size: 2rem;
            }

            .navbar-collapse {
                background: rgba(45, 31, 20, 0.98);
                margin-top: 1rem;
                padding: 1rem;
                border-radius: 10px;
                backdrop-filter: blur(20px);
            }
        }

        @media (max-width: 768px) {
            section {
                padding: 60px 0;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .section-title h2 {
                font-size: 1.75rem;
            }

            .about-features {
                grid-template-columns: 1fr;
            }

            .contact-cards {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--dark-color);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loading-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top-color: var(--accent-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-landing" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="#beranda">
                @if (!empty($settings['company_logo']))
                    <img src="{{ asset('storage/' . $settings['company_logo']) }}"
                        alt="{{ $settings['company_name'] ?? 'Logo' }}">
                @else
                    <i class='bx bxs-coffee'></i>
                @endif
                {{ $settings['company_name'] ?? 'Eureka Kopi' }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="#beranda">{{ __('landing.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#produk">{{ __('landing.products') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">{{ __('landing.about') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#promo"><i class='bx bxs-discount me-1'></i>Promo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tracking.jne') }}" target="_blank"><i
                                class='bx bx-package me-1'></i>Lacak Pengiriman</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">{{ __('landing.contact') }}</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link btn-login" href="{{ route('login') }}">
                            <i class='bx bx-log-in me-1'></i>{{ __('landing.login') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section / Carousel -->
    <section class="hero-section" id="beranda">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                @forelse($slides as $slide)
                    <div class="swiper-slide hero-slide">
                        <div class="hero-slide-bg {{ !$slide->image ? 'no-image' : '' }}"
                            @if ($slide->image) style="background-image: url('{{ asset('storage/' . $slide->image) }}');" @endif>
                        </div>
                        <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
                            <h1>{{ $slide->title }}</h1>
                            <p>{{ $slide->subtitle }}</p>
                            @if ($slide->button_text)
                                <a href="{{ $slide->button_link ?? '#produk' }}" class="hero-btn">
                                    {{ $slide->button_text }}
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide hero-slide">
                        <div class="hero-slide-bg no-image"></div>
                        <div class="hero-content">
                            <h1>{{ $settings['landing_hero_title'] ?? __('landing.hero_title') }}</h1>
                            <p>{{ $settings['landing_hero_subtitle'] ?? __('landing.hero_subtitle') }}
                            </p>
                            <a href="#produk" class="hero-btn">{{ __('landing.hero_cta') }}</a>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="produk">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>{{ $settings['landing_product_title'] ?? __('landing.products_title') }}</h2>
                <p>{{ $settings['landing_product_subtitle'] ?? __('landing.products_subtitle') }}
                </p>
            </div>

            <!-- Category Filters -->
            <div class="category-filters" data-aos="fade-up" data-aos-delay="100">
                <button class="category-btn active"
                    data-category="all">{{ __('landing.view_all_products') }}</button>
                @foreach ($categories as $category)
                    <button class="category-btn" data-category="{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <!-- Products Carousel -->
            <div class="swiper products-swiper" data-aos="fade-up" data-aos-delay="200">
                <div class="swiper-wrapper" id="productsContainer">
                    @foreach ($products as $product)
                        <div class="swiper-slide" data-category="{{ $product->category_id }}">
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/placeholder-product.png') }}"
                                        alt="{{ $product->name }}">
                                    <span
                                        class="product-category-badge">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                </div>
                                <div class="product-info">
                                    <h3>{{ $product->name }}</h3>
                                    <div class="price">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                        <span class="unit">/ {{ $product->unit ?? 'kg' }}</span>
                                    </div>
                                    <button class="btn-detail" onclick="showProductDetail({{ $product->id }})">
                                        <i class='bx bx-info-circle me-1'></i>{{ __('general.detail') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang">
        <div class="container">
            <div class="about-content">
                <div class="about-image" data-aos="fade-right">
                    <img src="{{ !empty($settings['landing_about_image']) ? asset('storage/' . $settings['landing_about_image']) : asset('assets/img/landing/about-default.jpg') }}"
                        alt="{{ __('landing.about_title') }}">
                </div>
                <div class="about-text" data-aos="fade-left">
                    <h2>{{ $settings['landing_about_title'] ?? __('landing.about_title') }}</h2>
                    <p>{{ $settings['landing_about_content'] ?? __('landing.about_description') }}
                    </p>

                    <div class="about-features">
                        <div class="about-feature">
                            <i class='bx bx-check-shield'></i>
                            <span>{{ __('landing.feature_quality') }}</span>
                        </div>
                        <div class="about-feature">
                            <i class='bx bx-package'></i>
                            <span>{{ __('landing.feature_delivery') }}</span>
                        </div>
                        <div class="about-feature">
                            <i class='bx bx-money'></i>
                            <span>Harga Kompetitif</span>
                        </div>
                        <div class="about-feature">
                            <i class='bx bx-support'></i>
                            <span>{{ __('landing.feature_support') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Promo Section -->
    <section id="promo">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>{{ $settings['landing_promo_title'] ?? 'Promo Spesial' }}</h2>
                <p>{{ $settings['landing_promo_subtitle'] ?? 'Dapatkan penawaran terbaik dari kami!' }}</p>
            </div>

            <div class="swiper promo-swiper" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper">
                    @forelse($promos as $promo)
                        <div class="swiper-slide">
                            <div class="promo-card">
                                <div class="promo-image">
                                    <img src="{{ $promo->image ? asset('storage/' . $promo->image) : asset('assets/img/placeholder-promo.png') }}"
                                        alt="{{ $promo->title }}">
                                    @if ($promo->discount_value > 0)
                                        <span class="promo-badge">
                                            @if ($promo->discount_type === 'percentage')
                                                {{ number_format($promo->discount_value, 0) }}% OFF
                                            @else
                                                Hemat Rp {{ number_format($promo->discount_value, 0, ',', '.') }}
                                            @endif
                                        </span>
                                    @endif
                                </div>
                                <div class="promo-content">
                                    <h4>{{ $promo->title }}</h4>
                                    @if ($promo->description)
                                        <p>{{ Str::limit($promo->description, 100) }}</p>
                                    @endif
                                    @if ($promo->promo_code)
                                        <div class="promo-code">
                                            <i class='bx bxs-coupon me-1'></i>{{ $promo->promo_code }}
                                        </div>
                                    @endif
                                    @if ($promo->end_date)
                                        <div class="promo-validity">
                                            <i class='bx bx-time-five'></i>
                                            <span>Berlaku s/d {{ $promo->end_date->format('d M Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="promo-card">
                                <div class="promo-image"
                                    style="background: linear-gradient(135deg, var(--accent-color), var(--secondary-color)); display: flex; align-items: center; justify-content: center;">
                                    <i class='bx bxs-discount' style="font-size: 4rem; color: #fff;"></i>
                                </div>
                                <div class="promo-content">
                                    <h4>Promo Segera Hadir!</h4>
                                    <p>Nantikan berbagai penawaran menarik dari kami. Pantau terus halaman ini!</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>{{ $settings['landing_contact_title'] ?? __('landing.contact_title') }}</h2>
                <p>{{ $settings['landing_contact_subtitle'] ?? __('landing.contact_subtitle') }}
                </p>
            </div>

            <div class="contact-cards" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-card">
                    <i class='bx bx-envelope'></i>
                    <h4>{{ __('landing.contact_email') }}</h4>
                    <p>
                        <a href="mailto:{{ $settings['company_email'] ?? 'info@eurekakopi.com' }}">
                            {{ $settings['company_email'] ?? 'info@eurekakopi.com' }}
                        </a>
                    </p>
                </div>
                <div class="contact-card">
                    <i class='bx bx-phone'></i>
                    <h4>{{ __('landing.contact_phone') }}</h4>
                    <p>
                        <a href="tel:{{ $settings['company_phone'] ?? '081234567890' }}">
                            {{ $settings['company_phone'] ?? '081234567890' }}
                        </a>
                    </p>
                </div>
                <div class="contact-card">
                    <i class='bx bx-map'></i>
                    <h4>{{ __('landing.contact_address') }}</h4>
                    <p>{{ $settings['company_address'] ?? 'Jl. Kopi No. 1, Jakarta' }}</p>
                </div>
            </div>

            <div class="social-links" data-aos="fade-up" data-aos-delay="200">
                @if (!empty($settings['landing_whatsapp']))
                    <a href="https://wa.me/{{ $settings['landing_whatsapp'] }}" target="_blank" class="social-link"
                        title="WhatsApp">
                        <i class='bx bxl-whatsapp'></i>
                    </a>
                @endif
                @if (!empty($settings['landing_instagram']))
                    <a href="https://instagram.com/{{ str_replace('@', '', $settings['landing_instagram']) }}"
                        target="_blank" class="social-link" title="Instagram">
                        <i class='bx bxl-instagram'></i>
                    </a>
                @endif
                @if (!empty($settings['landing_facebook']))
                    <a href="https://facebook.com/{{ $settings['landing_facebook'] }}" target="_blank"
                        class="social-link" title="Facebook">
                        <i class='bx bxl-facebook'></i>
                    </a>
                @endif
                @if (!empty($settings['landing_email']))
                    <a href="mailto:{{ $settings['landing_email'] }}" class="social-link" title="Email">
                        <i class='bx bx-envelope'></i>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} {{ $settings['company_name'] ?? 'Eureka Kopi' }}.
                {{ __('landing.footer_rights') }}.</p>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    @if (!empty($settings['landing_whatsapp']))
        <div class="whatsapp-float-container">
            <span class="whatsapp-float-label">🔥 Special Price</span>
            <a href="https://wa.me/{{ $settings['landing_whatsapp'] }}?text={{ urlencode('Halo, saya tertarik dengan promo Special Price!') }}"
                target="_blank" class="whatsapp-float" title="Chat via WhatsApp untuk Special Price">
                <i class='bx bxl-whatsapp'></i>
            </a>
        </div>
    @endif

    <!-- Product Detail Modal -->
    <div class="modal fade" id="productDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('landing.view_details') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <img src="" alt="" class="product-detail-img" id="modalProductImage">
                        </div>
                        <div class="col-md-7 product-detail-info">
                            <h4 id="modalProductName"></h4>
                            <p class="product-detail-price" id="modalProductPrice"></p>
                            <p class="text-muted" id="modalProductDescription"></p>

                            <table class="table product-detail-table">
                                <tbody>
                                    <tr>
                                        <td>{{ __('general.category') }}</td>
                                        <td id="modalProductCategory"></td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('general.type') }}</td>
                                        <td id="modalProductType"></td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('products.weight') }}</td>
                                        <td id="modalProductWeight"></td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('products.min_order') }}</td>
                                        <td id="modalProductMinOrder"></td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('inventory.stock') }}</td>
                                        <td id="modalProductStock"></td>
                                    </tr>
                                </tbody>
                            </table>

                            <a href="{{ route('login') }}" class="btn btn-lg w-100"
                                style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: #fff;">
                                <i class='bx bx-cart me-2'></i>{{ __('landing.login_to_order') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert2 Coffee Theme Configuration
        const swalCoffee = Swal.mixin({
            confirmButtonColor: '#8B5A2B',
            cancelButtonColor: '#6c757d',
            iconColor: '#8B5A2B',
        });

        // Auto-show SweetAlert for session messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#8B5A2B',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#8B5A2B'
            });
        @endif

        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: '{{ session('warning') }}',
                confirmButtonColor: '#8B5A2B'
            });
        @endif

        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Loading Screen
        window.addEventListener('load', function() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            // Update active nav link based on scroll position
            updateActiveNavLink();
        });

        // Update active nav link
        function updateActiveNavLink() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.navbar-landing .nav-link:not(.btn-login)');

            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionHeight = section.offsetHeight;
                const scrollY = window.scrollY;

                if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                    const currentId = section.getAttribute('id');
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + currentId) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }

        // Smooth scroll for nav links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Close mobile menu
                    const navbarCollapse = document.querySelector('.navbar-collapse');
                    if (navbarCollapse.classList.contains('show')) {
                        new bootstrap.Collapse(navbarCollapse).hide();
                    }
                }
            });
        });

        // Initialize Hero Swiper
        const heroSwiper = new Swiper('.hero-swiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            pagination: {
                el: '.hero-swiper .swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.hero-swiper .swiper-button-next',
                prevEl: '.hero-swiper .swiper-button-prev',
            },
        });

        // Initialize Products Swiper
        let productsSwiper = new Swiper('.products-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            pagination: {
                el: '.products-swiper .swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                576: {
                    slidesPerView: 2,
                },
                992: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 4,
                }
            }
        });

        // Initialize Promo Swiper
        const promoSwiper = new Swiper('.promo-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            pagination: {
                el: '.promo-swiper .swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            breakpoints: {
                576: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                992: {
                    slidesPerView: 4,
                }
            }
        });

        // Category Filter
        const categoryBtns = document.querySelectorAll('.category-btn');
        categoryBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                categoryBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const categoryId = this.getAttribute('data-category');
                filterProducts(categoryId);
            });
        });

        function filterProducts(categoryId) {
            const slides = document.querySelectorAll('.products-swiper .swiper-slide');
            let visibleCount = 0;

            slides.forEach(slide => {
                if (categoryId === 'all' || slide.getAttribute('data-category') === categoryId) {
                    slide.style.display = '';
                    visibleCount++;
                } else {
                    slide.style.display = 'none';
                }
            });

            // Update swiper
            productsSwiper.update();
        }

        // Show Product Detail
        function showProductDetail(productId) {
            fetch(`/landing/product/${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const product = data.product;
                        document.getElementById('modalProductImage').src = product.image_url;
                        document.getElementById('modalProductName').textContent = product.name;
                        document.getElementById('modalProductPrice').textContent = product.formatted_price + ' / ' +
                            product.unit;
                        document.getElementById('modalProductDescription').textContent = product.description || '-';
                        document.getElementById('modalProductCategory').textContent = product.category;
                        document.getElementById('modalProductType').textContent = product.type;
                        document.getElementById('modalProductWeight').textContent = product.weight + ' ' + product.unit;
                        document.getElementById('modalProductMinOrder').textContent = product.min_order_qty + ' ' +
                            product.unit;
                        document.getElementById('modalProductStock').textContent = product.stock > 0 ?
                            product.stock + ' ' + product.unit + ' tersedia' : 'Stok habis';

                        const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
                        modal.show();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal memuat detail produk',
                        confirmButtonColor: '#8B5A2B'
                    });
                });
        }
    </script>
</body>

</html>
