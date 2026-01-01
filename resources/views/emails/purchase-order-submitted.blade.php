{{-- File: resources/views/emails/purchase-order-submitted.blade.php --}}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order Menunggu Approval</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
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
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            background: #f8f9fa;
            font-weight: bold;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }

        .button:hover {
            background: #5568d3;
        }

        .button-approve {
            background: #28a745;
        }

        .button-approve:hover {
            background: #218838;
        }

        .button-reject {
            background: #dc3545;
        }

        .button-reject:hover {
            background: #c82333;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 12px;
        }

        .alert {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>📋 Purchase Order Baru</h1>
        <p>Menunggu Persetujuan Anda</p>
    </div>

    <div class="content">
        <p>Yth. Bapak/Ibu <strong>Owner</strong>,</p>

        <p>Purchase Order baru telah dibuat dan menunggu persetujuan Anda.</p>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #667eea;">Informasi Purchase Order</h3>

            <div class="info-row">
                <span class="label">Nomor PO:</span>
                <span class="value"><strong>{{ $purchaseOrder->po_number }}</strong></span>
            </div>

            <div class="info-row">
                <span class="label">Supplier:</span>
                <span class="value">{{ $purchaseOrder->supplier->name }}</span>
            </div>

            <div class="info-row">
                <span class="label">Dibuat Oleh:</span>
                <span class="value">{{ $purchaseOrder->creator->name }}</span>
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

        @if ($purchaseOrder->notes)
            <div class="alert">
                <strong>📝 Catatan:</strong><br>
                {{ $purchaseOrder->notes }}
            </div>
        @endif

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
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Subtotal:</td>
                    <td style="text-align: right;">Rp {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Pajak ({{ setting('tax_rate', 11) }}%):</td>
                    <td style="text-align: right;">Rp {{ number_format($purchaseOrder->tax_amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right; font-size: 16px;">TOTAL:</td>
                    <td style="text-align: right; font-size: 16px; color: #28a745;">
                        Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('owner.purchase-orders.show', $purchaseOrder->id) }}" class="button">
                🔍 Lihat Detail & Approve
            </a>
        </div>

        <p style="margin-top: 30px; font-size: 14px; color: #6c757d;">
            Silakan login ke sistem untuk menyetujui atau menolak Purchase Order ini.
        </p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ setting('company_name', 'Eureka Kopi') }}. All rights reserved.</p>
        <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
    </div>
</body>

</html>
