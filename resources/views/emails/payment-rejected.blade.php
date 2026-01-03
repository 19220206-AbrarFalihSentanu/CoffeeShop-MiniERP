{{-- File: resources/views/emails/payment-rejected.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Ditolak</title>
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
            background-color: #ff3e1d;
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
            border-left: 4px solid #ff3e1d;
        }
        .order-info p {
            margin: 10px 0;
        }
        .order-info strong {
            color: #ff3e1d;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #696cff;
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
        .alert-danger {
            background-color: #f8d7da;
            border-left: 4px solid #ff3e1d;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .reason-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">⚠️ Pembayaran Ditolak</h1>
    </div>

    <div class="content">
        <p>Halo <strong>{{ $payment->order->customer_name }}</strong>,</p>

        <div class="alert-danger">
            <strong>Pembayaran Anda untuk order <span style="color: #ff3e1d;">{{ $payment->order->order_number }}</span> tidak dapat diverifikasi.</strong>
        </div>

        <div class="reason-box">
            <strong>📌 Alasan Penolakan:</strong><br>
            <p style="margin-top: 10px; margin-bottom: 0;">{{ $payment->rejection_reason }}</p>
        </div>

        <div class="order-info">
            <p><strong>Order Number:</strong> {{ $payment->order->order_number }}</p>
            <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
            <p><strong>Ditolak Pada:</strong> {{ $payment->rejected_at->format('d M Y, H:i') }}</p>
        </div>

        <p><strong>Apa yang harus dilakukan selanjutnya?</strong></p>
        <ol>
            <li>Periksa kembali bukti pembayaran Anda</li>
            <li>Pastikan bukti pembayaran jelas dan sesuai dengan jumlah yang harus dibayar</li>
            <li>Upload ulang bukti pembayaran yang benar melalui dashboard Anda</li>
        </ol>

        <p><strong>Tips untuk Upload Bukti Pembayaran:</strong></p>
        <ul>
            <li>✅ Pastikan foto/screenshot jelas dan tidak buram</li>
            <li>✅ Pastikan jumlah transfer sesuai dengan total order</li>
            <li>✅ Pastikan nama pengirim dan tanggal transfer terlihat</li>
            <li>✅ Format file: JPG, PNG (maksimal 2MB)</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ route('customer.orders.show', $payment->order) }}" class="btn">
                Upload Ulang Bukti Pembayaran
            </a>
        </div>

        <p style="margin-top: 30px;">Jika Anda memiliki pertanyaan atau butuh bantuan, silakan hubungi kami.</p>

        <p style="margin-top: 20px;">Terima kasih atas pengertian Anda,<br><strong>Tim Mini ERP Kopi</strong></p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        <p>Untuk pertanyaan, hubungi: <a href="mailto:info@mini-erp-kopi.com">info@mini-erp-kopi.com</a></p>
        <p>&copy; {{ date('Y') }} Mini ERP Kopi. All rights reserved.</p>
    </div>
</body>
</html>