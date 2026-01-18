{{-- File: resources/views/emails/order-shipped.blade.php --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Dikirim</title>
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
            background: linear-gradient(135deg, #696cff 0%, #5f61e6 100%);
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .order-info p {
            margin: 10px 0;
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

        .shipping-banner {
            background-color: #cfe2ff;
            border: 2px solid #696cff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }

        .tracking-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
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
    </style>
</head>

<body>
    <div class="header">
        <h1 style="margin: 0;">🚚 Pesanan Sedang Dikirim!</h1>
        <p style="margin: 10px 0 0 0; font-size: 16px;">Pesanan Anda dalam perjalanan</p>
    </div>

    <div class="content">
        <p>Halo <strong>{{ $order->customer_name }}</strong>,</p>

        <div class="shipping-banner">
            <h2 style="color: #696cff; margin: 0 0 10px 0;">📦 Pesanan Dalam Pengiriman</h2>
            <p style="margin: 0; font-size: 18px;">Order <strong>{{ $order->order_number }}</strong></p>
        </div>

        <p>Kabar baik! Pesanan Anda telah dikirim dan sedang dalam perjalanan menuju alamat tujuan.</p>

        @if ($order->tracking_number)
            <div class="tracking-box">
                <strong>📍 Nomor Resi / Tracking:</strong>
                <p style="margin: 10px 0 0 0; font-size: 20px; font-weight: bold; color: #333;">
                    {{ $order->tracking_number }}
                </p>
                <small style="color: #666;">Gunakan nomor ini untuk melacak pengiriman Anda</small>
            </div>
        @endif

        <div class="order-info">
            <h3 style="margin-top: 0; color: #696cff;">📋 Detail Pengiriman</h3>

            <p><strong>Alamat Pengiriman:</strong></p>
            <p style="background: #f8f9fa; padding: 10px; border-radius: 4px;">
                {{ $order->customer_name }}<br>
                {{ $order->customer_phone }}<br>
                {{ $order->shipping_address }}
            </p>

            <p><strong>Tanggal Pengiriman:</strong> {{ $order->shipped_at->format('d M Y, H:i') }}</p>
            <p><strong>Total Pesanan:</strong> <span style="color: #696cff; font-weight: bold;">Rp
                    {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
        </div>

        <div class="order-info">
            <h3 style="margin-top: 0; color: #696cff;">📦 Produk yang Dikirim</h3>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th style="text-align: center;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p><strong>📝 Tips Penerimaan:</strong></p>
        <ul>
            <li>Pastikan nomor telepon Anda aktif untuk dihubungi kurir</li>
            <li>Periksa kondisi paket saat menerima barang</li>
            <li>Konfirmasi penerimaan setelah barang diterima</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ route('customer.orders.show', $order) }}" class="btn">
                Lihat Detail Pesanan
            </a>
        </div>

        <p style="margin-top: 30px;">
            Jika ada kendala dalam pengiriman, silakan hubungi kami segera.
        </p>

        <p>Salam hangat,<br><strong>Tim Eureka Kopi</strong></p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        <p>&copy; {{ date('Y') }} Eureka Kopi. All rights reserved.</p>
    </div>
</body>

</html>


