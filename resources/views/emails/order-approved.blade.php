{{-- File: resources/views/emails/order-approved.blade.php --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Disetujui</title>
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
            background: linear-gradient(135deg, #71dd37 0%, #5cb85c 100%);
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
            background-color: #71dd37;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        .btn:hover {
            background-color: #5cb85c;
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

        .payment-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .bank-info {
            background-color: #e7f1ff;
            border: 1px solid #696cff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="margin: 0;">✅ Pesanan Disetujui!</h1>
        <p style="margin: 10px 0 0 0; font-size: 16px;">Segera lakukan pembayaran</p>
    </div>

    <div class="content">
        <p>Halo <strong>{{ $order->customer_name }}</strong>,</p>

        <div class="success-banner">
            <h2 style="color: #71dd37; margin: 0 0 10px 0;">🎉 Pesanan Anda Telah Disetujui!</h2>
            <p style="margin: 0; font-size: 18px;">Order <strong>{{ $order->order_number }}</strong></p>
        </div>

        <p>Kabar baik! Pesanan Anda telah disetujui dan siap untuk diproses. Silakan lakukan pembayaran untuk
            melanjutkan proses pengiriman.</p>

        <div class="order-info">
            <h3 style="margin-top: 0; border-bottom: 2px solid #71dd37; padding-bottom: 10px;">📋 Detail Pesanan</h3>

            <p>
                <span class="label">Nomor Pesanan</span><br>
                <span class="value">{{ $order->order_number }}</span>
            </p>

            <p>
                <span class="label">Tanggal Pesanan</span><br>
                <span class="value">{{ $order->created_at->format('d F Y, H:i') }}</span>
            </p>

            @if ($order->due_date)
                <p>
                    <span class="label">Jatuh Tempo Pembayaran</span><br>
                    <span class="value"
                        style="color: #ff3e1d;">{{ \Carbon\Carbon::parse($order->due_date)->format('d F Y') }}</span>
                </p>
            @endif

            <p>
                <span class="label">Status</span><br>
                <span class="value" style="color: #71dd37;">✅ Disetujui</span>
            </p>
        </div>

        <div class="order-info">
            <h3 style="margin-top: 0; border-bottom: 2px solid #71dd37; padding-bottom: 10px;">🛒 Item Pesanan</h3>

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
                        <td colspan="3" style="text-align: right;">Total Pembayaran:</td>
                        <td style="text-align: right; color: #71dd37; font-size: 18px;">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="payment-box">
            <h3 style="margin-top: 0; color: #856404;">💳 Informasi Pembayaran</h3>
            <p style="margin-bottom: 15px;">Silakan transfer ke salah satu rekening berikut:</p>

            @php
                $bankSettings = [
                    ['bank' => 'bank_name_1', 'account' => 'bank_account_1', 'holder' => 'bank_holder_1'],
                    ['bank' => 'bank_name_2', 'account' => 'bank_account_2', 'holder' => 'bank_holder_2'],
                    ['bank' => 'bank_name_3', 'account' => 'bank_account_3', 'holder' => 'bank_holder_3'],
                ];
            @endphp

            @foreach ($bankSettings as $bank)
                @php
                    $bankName = setting($bank['bank']);
                    $bankAccount = setting($bank['account']);
                    $bankHolder = setting($bank['holder']);
                @endphp

                @if ($bankName && $bankAccount)
                    <div class="bank-info">
                        <strong>{{ $bankName }}</strong><br>
                        No. Rekening: <strong>{{ $bankAccount }}</strong><br>
                        @if ($bankHolder)
                            a.n. <strong>{{ $bankHolder }}</strong>
                        @endif
                    </div>
                @endif
            @endforeach

            <p style="margin: 15px 0 0 0; font-size: 14px; color: #856404;">
                <strong>Penting:</strong> Setelah melakukan pembayaran, harap upload bukti transfer melalui sistem kami.
            </p>
        </div>

        @if ($order->shipping_address)
            <div class="order-info">
                <h3 style="margin-top: 0; border-bottom: 2px solid #71dd37; padding-bottom: 10px;">📍 Alamat Pengiriman
                </h3>
                <p style="margin: 0;">{{ $order->shipping_address }}</p>
            </div>
        @endif

        <p><strong>📎 Invoice terlampir</strong> pada email ini dalam format PDF.</p>

        <div style="text-align: center;">
            <a href="{{ route('customer.orders.index') }}" class="btn">Lihat Detail Pesanan</a>
        </div>

        <p style="color: #6c757d; font-size: 14px;">
            Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.
        </p>
    </div>

    <div class="footer">
        <p>Terima kasih telah memilih <strong>{{ config('app.name') }}</strong></p>
        <p style="font-size: 12px; color: #999;">
            Email ini dikirim otomatis. Jika ada pertanyaan, silakan hubungi kami.
        </p>
    </div>
</body>

</html>
