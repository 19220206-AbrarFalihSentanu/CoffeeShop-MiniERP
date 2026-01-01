{{-- File: resources/views/emails/purchase-order-rejected.blade.php --}}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order Ditolak</title>
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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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

        .rejected-badge {
            background: #dc3545;
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

        .reason-box {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .reason-box h3 {
            color: #721c24;
            margin-top: 0;
        }

        .reason-box p {
            color: #721c24;
            margin: 0;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }

        .next-steps {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .next-steps h3 {
            color: #856404;
            margin-top: 0;
        }

        .next-steps ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .next-steps li {
            margin: 8px 0;
            color: #856404;
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
        <h1>❌ Purchase Order Ditolak</h1>
        <p>Mohon perhatikan alasan penolakan</p>
    </div>

    <div class="content">
        <p>Yth. <strong>{{ $purchaseOrder->creator->name }}</strong>,</p>

        <div class="rejected-badge">
            ✗ REJECTED
        </div>

        <p>Purchase Order Anda telah <strong>DITOLAK</strong> oleh
            <strong>{{ $purchaseOrder->approver->name ?? 'Owner' }}</strong>.
        </p>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #dc3545;">Informasi Purchase Order</h3>

            <div class="info-row">
                <span class="label">Nomor PO:</span>
                <span class="value"><strong>{{ $purchaseOrder->po_number }}</strong></span>
            </div>

            <div class="info-row">
                <span class="label">Supplier:</span>
                <span class="value">{{ $purchaseOrder->supplier->name }}</span>
            </div>

            <div class="info-row">
                <span class="label">Tanggal Ditolak:</span>
                <span
                    class="value">{{ $purchaseOrder->rejected_at ? $purchaseOrder->rejected_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span>
            </div>

            <div class="info-row">
                <span class="label">Ditolak Oleh:</span>
                <span class="value">{{ $purchaseOrder->approver->name ?? 'Owner' }}</span>
            </div>

            <div class="info-row">
                <span class="label">Total Amount:</span>
                <span class="value" style="font-size: 18px; font-weight: bold;">
                    Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="reason-box">
            <h3>📝 Alasan Penolakan:</h3>
            <p style="font-size: 16px; line-height: 1.6;">
                {{ $purchaseOrder->rejection_reason }}
            </p>
        </div>

        <div class="next-steps">
            <h3>⚠️ Langkah Selanjutnya:</h3>
            <ul>
                <li>Review alasan penolakan di atas</li>
                <li>Diskusikan dengan Owner jika ada yang kurang jelas</li>
                <li>Anda dapat mengedit dan submit ulang PO ini</li>
                <li>Atau buat Purchase Order baru dengan penyesuaian</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('admin.purchase-orders.show', $purchaseOrder->id) }}" class="button">
                📋 Lihat Detail & Edit PO
            </a>
        </div>

        <p style="margin-top: 30px; font-size: 14px; color: #6c757d;">
            Jika ada pertanyaan, silakan hubungi Owner untuk diskusi lebih lanjut.
        </p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ setting('company_name', 'Eureka Kopi') }}. All rights reserved.</p>
        <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
    </div>
</body>

</html>
