{{-- File: resources/views/emails/purchase-order-approved.blade.php --}}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order Disetujui</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }

        .success-badge {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            margin: 20px 0;
            font-weight: bold;
        }

        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: bold;
            color: #6c757d;
        }

        .value {
            color: #212529;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .items-table th {
            background: #28a745;
            color: white;
            padding: 12px;
            text-align: left;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }

        .next-steps {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .next-steps h3 {
            color: #155724;
            margin-top: 0;
        }

        .next-steps ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .next-steps li {
            margin: 8px 0;
            color: #155724;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>✅ Purchase Order Disetujui!</h1>
        <p>Silakan lanjutkan proses pemesanan</p>
    </div>

    <div class="content">
        <p>Yth. <strong>{{ $purchaseOrder->creator->name }}</strong>,</p>

        <div class="success-badge">
            ✓ APPROVED
        </div>

        <p>Purchase Order Anda telah <strong>DISETUJUI</strong> oleh
            <strong>{{ $purchaseOrder->approver->name ?? 'Owner' }}</strong>.</p>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #28a745;">Informasi Purchase Order</h3>

            <div class="info-row">
                <span class="label">Nomor PO:</span>
                <span class="value"><strong>{{ $purchaseOrder->po_number }}</strong></span>
            </div>

            <div class="info-row">
                <span class="label">Supplier:</span>
                <span class="value">{{ $purchaseOrder->supplier->name }}</span>
            </div>

            <div class="info-row">
                <span class="label">Tanggal Disetujui:</span>
                <span
                    class="value">{{ $purchaseOrder->approved_at ? $purchaseOrder->approved_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span>
            </div>

            <div class="info-row">
                <span class="label">Disetujui Oleh:</span>
                <span class="value">{{ $purchaseOrder->approver->name ?? 'Owner' }}</span>
            </div>

            <div class="info-row">
                <span class="label">Tanggal Pengiriman:</span>
                <span class="value">{{ $purchaseOrder->expected_delivery_date->format('d/m/Y') }}</span>
            </div>

            <div class="info-row">
                <span class="label">Total Amount:</span>
                <span class="value" style="font-size: 18px; font-weight: bold; color: #28a745;">
                    Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <h3>Detail Items:</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th style="text-align: right;">Qty (kg)</th>
                    <th style="text-align: right;">Harga/kg</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrder->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td style="text-align: right;">{{ number_format($item->quantity_ordered) }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="next-steps">
            <h3>📋 Langkah Selanjutnya:</h3>
            <ul>
                <li>Hubungi supplier untuk konfirmasi pesanan</li>
                <li>Monitor proses pengiriman</li>
                <li>Siapkan proses penerimaan barang</li>
                <li>Setelah barang diterima, lakukan <strong>Receive Stock</strong> di sistem</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('admin.purchase-orders.show', $purchaseOrder->id) }}" class="button">
                📦 Lihat Detail PO
            </a>
        </div>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ setting('company_name', 'Eureka Kopi') }}. All rights reserved.</p>
        <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
    </div>
</body>

</html>
