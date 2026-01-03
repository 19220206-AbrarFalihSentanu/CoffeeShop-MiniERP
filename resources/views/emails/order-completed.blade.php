{{-- File: resources/views/emails/order-completed.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Selesai</title>
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
            background: linear-gradient(135deg, #71dd37 0%, #5cb85c 100%);
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .order-info p {
            margin: 10px 0;
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
        .success-banner {
            background-color: #d1e7dd;
            border: 2px solid #71dd37;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th,
        .items-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .feedback-box {
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
        <h1 style="margin: 0;">🎉 Pesanan Selesai!</h1>
        <p style="margin: 10px 0 0 0; font-size: 16px;">Terima kasih telah berbelanja</p>
    </div>

    <div class="content">
        <p>Halo <strong>{{ $order->customer_name }}</strong>,</p>

        <div class="success-banner">
            <h2 style="color: #71dd37; margin: 0 0 10px 0;">✅ Pesanan Berhasil Diselesaikan</h2>
            <p style="margin: 0; font-size: 18px;">Order <strong>{{ $order->order_number }}</strong></p>
        </div>

        <p>Kami dengan senang hati menginformasikan bahwa pesanan Anda telah selesai diproses dan telah dikirim/diserahkan.</p>

        <div class="order-info">
            <h3 style="margin-top: 0; color: #71dd37;">📦 Detail Pesanan</h3>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold;">
                        <td colspan="2">Total</td>
                        <td style="text-align: right; color: #71dd37;">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <p><strong>Tanggal Selesai:</strong> {{ $order->completed_at->format('d M Y, H:i') }}</p>
        </div>

        <div class="feedback-box">
            <strong>💭 Kami Ingin Mendengar Pendapat Anda!</strong><br>
            <p style="margin-top: 10px; margin-bottom: 10px;">
                Bagaimana pengalaman berbelanja Anda? Feedback Anda sangat berarti untuk kami agar dapat terus meningkatkan layanan.
            </p>
            <p style="margin: 0;">
                <small>(Fitur review akan segera hadir!)</small>
            </p>
        </div>

        <p><strong>📋 Informasi Tambahan:</strong></p>
        <ul>
            <li>Invoice Anda dapat didownload melalui dashboard</li>
            <li>Untuk pertanyaan atau keluhan, hubungi customer service kami</li>
            <li>Simpan order number untuk referensi di masa mendatang</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ route('customer.orders.show', $order) }}" class="btn">
                Lihat Detail Pesanan
            </a>
        </div>

        <p style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #71dd37;">
            <strong>Terima kasih telah mempercayai Mini ERP Kopi!</strong><br>
            Kami berharap dapat melayani Anda kembali di masa mendatang. ☕
        </p>

        <p>Salam hangat,<br><strong>Tim Mini ERP Kopi</strong></p>
    </div>

    <div class="footer">
        <p><strong>Hubungi Kami:</strong></p>
        <p>📧 Email: info@mini-erp-kopi.com | 📱 WhatsApp: +62 812-3456-7890</p>
        <p>&copy; {{ date('Y') }} Mini ERP Kopi. All rights reserved.</p>
    </div>
</body>
</html>