<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan | {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .error-code {
            font-size: 100px;
            font-weight: 700;
            color: #ffc107;
            line-height: 1;
            margin-bottom: 10px;
        }

        .error-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .error-message {
            color: #6c757d;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .search-box {
            margin-bottom: 25px;
        }

        .search-box form {
            display: flex;
            gap: 10px;
            max-width: 350px;
            margin: 0 auto;
        }

        .search-box input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s;
        }

        .search-box input:focus {
            border-color: #696cff;
        }

        .search-box button {
            padding: 12px 20px;
            background: #696cff;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s;
        }

        .search-box button:hover {
            background: #5f61e6;
        }

        .btn-home {
            display: inline-block;
            padding: 14px 40px;
            background: linear-gradient(135deg, #696cff 0%, #5f61e6 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(105, 108, 255, 0.4);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(105, 108, 255, 0.5);
        }

        .suggestions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .suggestions h3 {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .suggestions-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .suggestions-links a {
            color: #696cff;
            text-decoration: none;
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .suggestions-links a:hover {
            background: #f0f0ff;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-icon">🔍</div>
        <div class="error-code">404</div>
        <h1 class="error-title">Halaman Tidak Ditemukan</h1>
        <p class="error-message">
            Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.
        </p>

        <a href="{{ url('/') }}" class="btn-home">Kembali ke Beranda</a>

        <div class="suggestions">
            <h3>Mungkin Anda mencari:</h3>
            <div class="suggestions-links">
                <a href="{{ route('landing') }}">Halaman Utama</a>
                <a href="{{ route('catalog.index') }}">Katalog Produk</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>
    </div>
</body>

</html>
