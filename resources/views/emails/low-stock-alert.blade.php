{{-- File: resources/views/emails/low-stock-alert.blade.php --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Stok Menipis</title>
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
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #333;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background-color: white;
            padding: 30px;
            border: 1px solid #dee2e6;
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

        .warning-banner {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }

        .stock-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .stock-table th,
        .stock-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .stock-table th {
            background-color: #696cff;
            color: white;
            font-weight: bold;
        }

        .stock-table tr:hover {
            background-color: #f8f9fa;
        }

        .stock-critical {
            color: #ff3e1d;
            font-weight: bold;
        }

        .stock-warning {
            color: #ffc107;
            font-weight: bold;
        }

        .stock-low {
            color: #fd7e14;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-critical {
            background-color: #f8d7da;
            color: #ff3e1d;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .summary-box {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .summary-item {
            padding: 10px;
        }

        .summary-number {
            font-size: 32px;
            font-weight: bold;
            color: #696cff;
        }

        .summary-label {
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="margin: 0;">⚠️ Peringatan Stok Menipis</h1>
        <p style="margin: 10px 0 0 0; font-size: 16px;">{{ $products->count() }} Produk membutuhkan perhatian</p>
    </div>

    <div class="content">
        <p>Halo <strong>Admin</strong>,</p>

        <div class="warning-banner">
            <h2 style="color: #856404; margin: 0 0 10px 0;">📊 Laporan Stok Mingguan</h2>
            <p style="margin: 0;">Berikut adalah produk dengan stok di bawah minimum.</p>
        </div>

        <div class="summary-box">
            <div class="summary-item">
                <div class="summary-number">{{ $products->count() }}</div>
                <div class="summary-label">Total Produk</div>
            </div>
            <div class="summary-item">
                <div class="summary-number" style="color: #ff3e1d;">{{ $products->where('stock', '<=', 5)->count() }}
                </div>
                <div class="summary-label">Stok Kritis (≤5)</div>
            </div>
            <div class="summary-item">
                <div class="summary-number" style="color: #ffc107;">{{ $products->where('stock', '>', 5)->count() }}
                </div>
                <div class="summary-label">Stok Rendah (>5)</div>
            </div>
        </div>

        <table class="stock-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th style="text-align: center;">Stok</th>
                    <th style="text-align: center;">Min. Stok</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                    @php
                        $productStock = $product->stock ?? ($product->inventory->quantity ?? 0);
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td style="text-align: center;"
                            class="{{ $productStock <= 5 ? 'stock-critical' : 'stock-warning' }}">
                            {{ $productStock }}
                        </td>
                        <td style="text-align: center;">
                            {{ $product->min_stock ?? 10 }}
                        </td>
                        <td style="text-align: center;">
                            @if ($productStock <= 5)
                                <span class="badge badge-critical">🚨 KRITIS</span>
                            @else
                                <span class="badge badge-warning">⚠️ RENDAH</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>📌 Rekomendasi:</strong></p>
        <ul>
            <li>Segera lakukan restocking untuk produk dengan stok kritis (≤5)</li>
            <li>Buat Purchase Order untuk supplier terkait</li>
            <li>Periksa riwayat penjualan untuk estimasi kebutuhan</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ route('admin.inventory.index') }}" class="btn">Kelola Inventory</a>
            <a href="{{ route('admin.purchase-orders.create') }}" class="btn"
                style="background-color: #71dd37;">Buat PO Baru</a>
        </div>

        <p style="color: #6c757d; font-size: 14px; margin-top: 20px;">
            Email ini dikirim otomatis setiap minggu. Anda dapat mengatur frekuensi pengiriman di pengaturan sistem.
        </p>
    </div>

    <div class="footer">
        <p><strong>{{ config('app.name') }}</strong></p>
        <p style="font-size: 12px; color: #999;">
            Laporan dibuat pada: {{ now()->format('d F Y, H:i') }} WIB
        </p>
    </div>
</body>

</html>
