{{-- File: resources/views/emails/payment-proof-uploaded.blade.php --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran Diterima</title>
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
            background-color: #696cff;
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
            border-left: 4px solid #696cff;
        }

        .order-info p {
            margin: 10px 0;
        }

        .order-info strong {
            color: #696cff;
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

        .alert {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="margin: 0;">📋 Bukti Pembayaran Diterima</h1>
    </div>

    <div class="content">
        <p>Halo Admin/Owner,</p>

        <p>Customer telah mengupload bukti pembayaran untuk order berikut:</p>

        <div class="order-info">
            <p><strong>Order Number:</strong> {{ $payment->order->order_number }}</p>
            <p><strong>Customer:</strong> {{ $payment->order->customer_name }}</p>
            <p><strong>Email:</strong> {{ $payment->order->customer_email }}</p>
            <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
            <p><strong>Metode:</strong> {{ $payment->payment_method_display }}</p>
            <p><strong>Upload Pada:</strong> {{ $payment->created_at->format('d M Y, H:i') }}</p>
            @if ($payment->customer_notes)
                <p><strong>Catatan Customer:</strong><br>{{ $payment->customer_notes }}</p>
            @endif
        </div>

        <div class="alert">
            <strong>⏰ Action Required:</strong> Mohon segera verifikasi bukti pembayaran ini untuk melanjutkan proses
            pesanan.
        </div>

        <div style="text-align: center;">
            <a href="{{ route('admin.payments.show', $payment) }}" class="btn">
                Verifikasi Sekarang
            </a>
        </div>

        <p style="margin-top: 30px;">Terima kasih,<br><strong>Eureka Kopi System</strong></p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        <p>&copy; {{ date('Y') }} Eureka Kopi. All rights reserved.</p>
    </div>
</body>

</html>


