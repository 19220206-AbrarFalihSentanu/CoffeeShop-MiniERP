{{-- File: resources/views/emails/order-rejected.blade.php --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Ditolak</title>
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
            background: linear-gradient(135deg, #ff3e1d 0%, #e63917 100%);
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

        .reject-banner {
            background-color: #f8d7da;
            border: 2px solid #ff3e1d;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }

        .reason-box {
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
        <h1 style="margin: 0;">❌ Pesanan Ditolak</h1>
        <p style="margin: 10px 0 0 0; font-size: 16px;">Mohon maaf atas ketidaknyamanannya</p>
    </div>

    <div class="content">
        <p>Halo <strong>{{ $order->customer_name }}</strong>,</p>

        <div class="reject-banner">
            <h2 style="color: #ff3e1d; margin: 0 0 10px 0;">⚠️ Pesanan Tidak Dapat Diproses</h2>
            <p style="margin: 0; font-size: 18px;">Order <strong>{{ $order->order_number }}</strong></p>
        </div>

        <p>Dengan berat hati kami informasikan bahwa pesanan Anda tidak dapat kami proses. Mohon maaf atas
            ketidaknyamanan ini.</p>

        @if ($order->rejection_reason || $order->notes)
            <div class="reason-box">
                <h3 style="margin-top: 0; color: #856404;">📝 Alasan Penolakan</h3>
                <p style="margin: 0;">
                    {{ $order->rejection_reason ?? ($order->notes ?? 'Tidak ada alasan yang diberikan.') }}</p>
            </div>
        @endif

        <div class="order-info">
            <h3 style="margin-top: 0; border-bottom: 2px solid #ff3e1d; padding-bottom: 10px;">📋 Detail Pesanan</h3>

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
                <span class="value" style="color: #ff3e1d;">❌ Ditolak</span>
            </p>
        </div>

        <div class="order-info">
            <h3 style="margin-top: 0; border-bottom: 2px solid #ff3e1d; padding-bottom: 10px;">🛒 Item Pesanan</h3>

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
                        <td style="text-align: right; font-size: 18px;">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <p>Silakan hubungi kami untuk informasi lebih lanjut atau jika Anda ingin membuat pesanan baru.</p>

        <div style="text-align: center;">
            <a href="{{ route('landing') }}" class="btn">Kunjungi Toko Kami</a>
        </div>

        <p style="color: #6c757d; font-size: 14px;">
            Kami sangat menghargai kepercayaan Anda dan berharap dapat melayani Anda di kesempatan berikutnya.
        </p>
    </div>

    <div class="footer">
        <p>Terima kasih atas pengertiannya</p>
        <p><strong>{{ config('app.name') }}</strong></p>
        <p style="font-size: 12px; color: #999;">
            Email ini dikirim otomatis. Jika ada pertanyaan, silakan hubungi kami.
        </p>
    </div>
</body>

</html>
