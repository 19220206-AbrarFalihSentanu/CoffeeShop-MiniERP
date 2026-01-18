{{-- File: resources/views/emails/order-created.blade.php --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $recipientType === 'customer' ? 'Konfirmasi Pesanan' : 'Pesanan Baru' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .header {
            background: linear-gradient(135deg, #696cff 0%, #5f61e6 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background-color: white;
            padding: 30px;
            border: 1px solid #dee2e6;
        }

        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
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

        .btn:hover {
            background-color: #5f61e6;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }

        .info-banner {
            background-color: #cfe2ff;
            border: 2px solid #696cff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }

        .admin-banner {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
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

        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .label {
            color: #6c757d;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="header">
        @if ($recipientType === 'customer')
            <h1 style="margin: 0;">📦 Pesanan Diterima!</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Terima kasih telah memesan</p>
        @else
            <h1 style="margin: 0;">🔔 Pesanan Baru</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Membutuhkan Approval</p>
        @endif
    </div>

    <div class="content">
        @if ($recipientType === 'customer')
            <p>Halo <strong>{{ $order->customer_name }}</strong>,</p>

            <div class="info-banner">
                <h2 style="color: #696cff; margin: 0 0 10px 0;">📋 Pesanan Anda Telah Diterima</h2>
                <p style="margin: 0; font-size: 18px;">Order <strong>{{ $order->order_number }}</strong></p>
            </div>

            <p>Pesanan Anda sedang menunggu konfirmasi dari admin. Kami akan segera memproses pesanan Anda.</p>
        @else
            <p>Halo <strong>Admin</strong>,</p>

            <div class="admin-banner">
                <h2 style="color: #ffc107; margin: 0 0 10px 0;">⏳ Pesanan Menunggu Approval</h2>
                <p style="margin: 0; font-size: 18px;">Order <strong>{{ $order->order_number }}</strong></p>
            </div>

            <p>Ada pesanan baru yang membutuhkan persetujuan Anda.</p>
        @endif

        <div class="order-info">
            <h3 style="margin-top: 0; border-bottom: 2px solid #696cff; padding-bottom: 10px;">📋 Detail Pesanan</h3>

            <p>
                <span class="label">Nomor Pesanan</span><br>
                <span class="value">{{ $order->order_number }}</span>
            </p>

            <p>
                <span class="label">Tanggal Pesanan</span><br>
                <span class="value">{{ $order->created_at->format('d F Y, H:i') }}</span>
            </p>

            <p>
                <span class="label">Status</span><br>
                <span class="value" style="color: #ffc107;">⏳ Menunggu Approval</span>
            </p>
        </div>

        @if ($recipientType === 'admin')
            <div class="order-info">
                <h3 style="margin-top: 0; border-bottom: 2px solid #696cff; padding-bottom: 10px;">👤 Info Customer</h3>

                <p>
                    <span class="label">Nama</span><br>
                    <span class="value">{{ $order->customer_name }}</span>
                </p>

                @if ($order->customer_email)
                    <p>
                        <span class="label">Email</span><br>
                        <span class="value">{{ $order->customer_email }}</span>
                    </p>
                @endif

                @if ($order->customer_phone)
                    <p>
                        <span class="label">No. Telepon</span><br>
                        <span class="value">{{ $order->customer_phone }}</span>
                    </p>
                @endif
            </div>
        @endif

        <div class="order-info">
            <h3 style="margin-top: 0; border-bottom: 2px solid #696cff; padding-bottom: 10px;">🛒 Item Pesanan</h3>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Harga</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? $item->product_name }}</td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td style="text-align: right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td style="text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">Total:</td>
                        <td style="text-align: right; color: #696cff; font-size: 18px;">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if ($order->shipping_address)
            <div class="order-info">
                <h3 style="margin-top: 0; border-bottom: 2px solid #696cff; padding-bottom: 10px;">📍 Alamat Pengiriman
                </h3>
                <p style="margin: 0;">{{ $order->shipping_address }}</p>
            </div>
        @endif

        @if ($order->notes)
            <div class="order-info">
                <h3 style="margin-top: 0; border-bottom: 2px solid #696cff; padding-bottom: 10px;">📝 Catatan</h3>
                <p style="margin: 0;">{{ $order->notes }}</p>
            </div>
        @endif

        @if ($recipientType === 'customer')
            <p>Kami akan menghubungi Anda jika ada pertanyaan terkait pesanan ini.</p>
        @else
            <div style="text-align: center;">
                <a href="{{ route('admin.orders.index') }}" class="btn">Lihat & Approve Pesanan</a>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>Terima kasih telah memilih <strong>{{ config('app.name') }}</strong></p>
        <p style="font-size: 12px; color: #999;">
            Email ini dikirim otomatis. Jika ada pertanyaan, silakan hubungi kami.
        </p>
    </div>
</body>

</html>


