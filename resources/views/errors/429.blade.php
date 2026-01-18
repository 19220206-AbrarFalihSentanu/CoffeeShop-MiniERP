<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>429 - Terlalu Banyak Permintaan | {{ config('app.name') }}</title>
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
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
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
            color: #17a2b8;
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

        .countdown {
            font-size: 48px;
            font-weight: 700;
            color: #696cff;
            margin-bottom: 20px;
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
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-icon">🛑</div>
        <div class="error-code">429</div>
        <h1 class="error-title">Terlalu Banyak Permintaan</h1>
        <p class="error-message">
            Anda telah mengirimkan terlalu banyak permintaan dalam waktu singkat. Silakan tunggu beberapa saat sebelum
            mencoba lagi.
        </p>
        <div class="countdown" id="countdown">60</div>
        <p style="color: #6c757d; font-size: 14px; margin-bottom: 20px;">detik sebelum Anda dapat mencoba lagi</p>
        <a href="{{ url('/') }}" class="btn-home">Kembali ke Beranda</a>
    </div>

    <script>
        let seconds = 60;
        const countdown = document.getElementById('countdown');
        const interval = setInterval(() => {
            seconds--;
            countdown.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(interval);
                location.reload();
            }
        }, 1000);
    </script>
</body>

</html>


