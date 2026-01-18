<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error | {{ config('app.name') }}</title>
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
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a5a 100%);
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
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .error-code {
            font-size: 100px;
            font-weight: 700;
            color: #ff3e1d;
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
            margin-right: 10px;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(105, 108, 255, 0.5);
        }

        .btn-refresh {
            display: inline-block;
            padding: 14px 30px;
            background: #f8f9fa;
            color: #333;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-refresh:hover {
            background: #e9ecef;
        }

        .contact-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }

        .contact-info a {
            color: #696cff;
            text-decoration: none;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <div class="error-code">500</div>
        <h1 class="error-title">Terjadi Kesalahan Server</h1>
        <p class="error-message">
            Maaf, terjadi kesalahan pada server kami. Tim teknis kami sudah diberitahu dan sedang menangani masalah ini.
        </p>
        <div class="actions">
            <a href="{{ url('/') }}" class="btn-home">Kembali ke Beranda</a>
            <button onclick="location.reload()" class="btn-refresh">🔄 Coba Lagi</button>
        </div>
        <div class="contact-info">
            <p>Jika masalah berlanjut, silakan hubungi kami.</p>
        </div>
    </div>
</body>

</html>


