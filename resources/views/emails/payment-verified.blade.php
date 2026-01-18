{{-- File: resources/views/emails/payment-verified.blade.php --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Diverifikasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #71dd37;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
        }

        .order-info {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border-left: 4px solid #71dd37;
        }

        .order-info p {
            margin: 10px 0;
        }

        .order-info strong {
            color: #71dd37;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #71dd37;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }

        .success-box {
            background-color: #d1e7dd;
            border-left: 4px solid #71dd37;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="margin: 0;">✅ Pembayaran Diverifikasi</h1>
    </div>

    <div class="content">
        <p>Halo <strong>{{ $payment->order->customer_name }}</strong>,</p>

        <div class="success-box">
            <strong>🎉 Kabar Baik!</strong><br>
            Pembayaran Anda telah berhasil diverifikasi dan pesanan Anda akan segera diproses.
        </div>

        <div class="order-info">
            <p><strong>Order Number:</strong> {{ $payment->order->order_number }}</p>
            <p><strong>Jumlah Dibayar:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
            <p><strong>Status:</strong> <span style="color: #71dd37;">Sudah Dibayar ✓</span></p>
            <p><strong>Diverifikasi Pada:</strong> {{ $payment->verified_at->format('d M Y, H:i') }}</p>
            @if ($payment->verifier)
                <p><strong>Diverifikasi Oleh:</strong> {{ $payment->verifier->name }}</p>
            @endif
        </div>

        <p><strong>Langkah Selanjutnya:</strong></p>
        <ul>
            <li>Pesanan Anda akan segera diproses oleh tim kami</li>
            <li>Anda akan menerima email konfirmasi saat pesanan dikirim</li>
            <li>Lacak status pesanan Anda kapan saja melalui dashboard</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ route('customer.orders.show', $payment->order) }}" class="btn">
                Lihat Detail Pesanan
            </a>
        </div>

        <p style="margin-top: 30px;">Terima kasih telah berbelanja di Eureka Kopi!</p>
        <p>Jika ada pertanyaan, jangan ragu untuk menghubungi kami.</p>

        <p style="margin-top: 20px;">Salam,<br><strong>Tim Eureka Kopi</strong></p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        <p>&copy; {{ date('Y') }} Eureka Kopi. All rights reserved.</p>
    </div>
</body>

</html>


