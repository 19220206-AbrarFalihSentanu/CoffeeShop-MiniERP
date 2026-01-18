<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            padding: 20px;
        }

        .layout-table {
            width: 100%;
            border-collapse: collapse;
        }

        .layout-table td {
            vertical-align: top;
            padding: 0;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #6F4E37;
            margin-bottom: 5px;
        }

        .company-info {
            font-size: 10px;
            color: #666;
            line-height: 1.6;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #6F4E37;
            margin-bottom: 10px;
        }

        .invoice-number {
            font-size: 12px;
            color: #333;
        }

        .invoice-number strong {
            color: #6F4E37;
        }

        /* Divider */
        .divider {
            border-bottom: 2px solid #6F4E37;
            margin: 20px 0;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #6F4E37;
            text-transform: uppercase;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .billing-info p,
        .detail-row {
            margin-bottom: 5px;
        }

        .detail-row span {
            color: #666;
        }

        .detail-row strong {
            color: #333;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background-color: #6F4E37;
            color: white;
            padding: 12px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }

        .items-table th.text-right {
            text-align: right;
        }

        .items-table th.text-center {
            text-align: center;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
        }

        .items-table tr:nth-child(even) {
            background-color: #fafafa;
        }

        .items-table .text-right {
            text-align: right;
        }

        .items-table .text-center {
            text-align: center;
        }

        .product-name {
            font-weight: bold;
            color: #333;
        }

        .product-sku {
            font-size: 9px;
            color: #999;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
        }

        .totals-table .total-row {
            background-color: #6F4E37;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .totals-table .total-row td {
            padding: 12px 10px;
            border: none;
        }

        .text-right {
            text-align: right;
        }

        /* Notes Box */
        .notes-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #6F4E37;
        }

        .notes-title {
            font-weight: bold;
            color: #6F4E37;
            margin-bottom: 8px;
        }

        .notes-content {
            font-size: 10px;
            color: #666;
        }

        /* Payment Info */
        .payment-info {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .payment-info h4 {
            color: #856404;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .payment-info p {
            font-size: 10px;
            color: #856404;
            margin-bottom: 5px;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #6F4E37;
            text-align: center;
        }

        .footer p {
            font-size: 9px;
            color: #666;
            margin-bottom: 5px;
        }

        .footer .thank-you {
            font-size: 14px;
            font-weight: bold;
            color: #6F4E37;
            margin-bottom: 10px;
        }

        .terms {
            font-size: 8px;
            color: #999;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-paid {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-shipped {
            background-color: #e2e3e5;
            color: #383d41;
        }
    </style>
</head>

<body>
    <!-- Header - Using Table for mPDF compatibility -->
    <table class="layout-table" style="margin-bottom: 20px;">
        <tr>
            <td style="width: 50%;">
                <div class="company-name">{{ $company['name'] }}</div>
                <div class="company-info">
                    {{ $company['address'] }}<br>
                    Email: {{ $company['email'] }}<br>
                    Phone: {{ $company['phone'] }}
                </div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">
                    <strong>#{{ $order->order_number }}</strong>
                </div>
                <div style="margin-top: 10px;">
                    <span class="status-badge status-{{ $order->status }}">{{ strtoupper($order->status) }}</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Billing & Invoice Details - Using Table for mPDF compatibility -->
    <table class="layout-table" style="margin-bottom: 30px;">
        <tr>
            <td style="width: 50%; padding-right: 20px;">
                <div class="section-title">Bill To</div>
                <div class="billing-info">
                    <p><strong>{{ $order->customer_name }}</strong></p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>Email: {{ $order->customer_email ?? ($order->customer->email ?? '-') }}</p>
                    <p>Phone: {{ $order->customer_phone ?? ($order->customer->phone ?? '-') }}</p>
                </div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="section-title" style="text-align: right;">Invoice Details</div>
                <div class="detail-row">
                    <span>Invoice Date:</span> <strong>{{ $invoice_date->format('d M Y') }}</strong>
                </div>
                <div class="detail-row">
                    <span>Order Date:</span> <strong>{{ $order->created_at->format('d M Y') }}</strong>
                </div>
                <div class="detail-row">
                    <span>Payment Method:</span>
                    <strong>{{ ucfirst(str_replace('_', ' ', $order->payment?->payment_method ?? 'Transfer Bank')) }}</strong>
                </div>
                @if ($order->approved_at)
                    <div class="detail-row">
                        <span>Approved:</span> <strong>{{ $order->approved_at->format('d M Y H:i') }}</strong>
                    </div>
                @endif
                @if ($order->due_date)
                    <div class="detail-row" style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #ddd;">
                        <span style="color: #dc3545;">Due Date:</span>
                        <strong style="color: #dc3545;">{{ $order->due_date->format('d M Y') }}</strong>
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 35%;">Product</th>
                <th style="width: 10%;" class="text-center">Unit</th>
                <th style="width: 15%;" class="text-center">Quantity</th>
                <th style="width: 17%;" class="text-right">Unit Price</th>
                <th style="width: 18%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $item->product_name }}</div>
                        @if ($item->product)
                            <div class="product-sku">SKU: {{ $item->product->sku }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->unit ?? 'kg' }}</td>
                    <td class="text-center">{{ rtrim(rtrim(number_format($item->quantity, 3, '.', ''), '0'), '.') }}
                    </td>
                    <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Section - Using Table for mPDF compatibility -->
    <table class="layout-table" style="margin-bottom: 30px;">
        <tr>
            <td style="width: 55%; vertical-align: top; padding-right: 30px;">
                @if ($order->customer_notes)
                    <div class="notes-box">
                        <div class="notes-title">Order Notes</div>
                        <div class="notes-content">{{ $order->customer_notes }}</div>
                    </div>
                @endif

                <div class="payment-info" style="margin-top: 15px;">
                    <h4>Payment Information</h4>

                    @php
                        $hasBank = false;
                        $banks = [];
                        for ($i = 1; $i <= 3; $i++) {
                            $bankName = setting("bank_name_{$i}");
                            $bankAccount = setting("bank_account_number_{$i}");
                            $bankHolder = setting("bank_account_name_{$i}");
                            if ($bankName && $bankAccount) {
                                $banks[] = [
                                    'name' => $bankName,
                                    'account' => $bankAccount,
                                    'holder' => $bankHolder ?: $company['name'],
                                ];
                                $hasBank = true;
                            }
                        }
                    @endphp

                    @if ($hasBank)
                        @foreach ($banks as $index => $bank)
                            <div
                                style="{{ $index > 0 ? 'margin-top: 10px; padding-top: 10px; border-top: 1px dashed #ddd;' : '' }}">
                                <p style="margin-bottom: 3px;"><strong>{{ $bank['name'] }}</strong></p>
                                <p style="margin-bottom: 3px;">Account: <strong>{{ $bank['account'] }}</strong></p>
                                <p style="margin-bottom: 0;">a/n: {{ $bank['holder'] }}</p>
                            </div>
                        @endforeach
                    @else
                        <p><strong>Bank Transfer:</strong> Bank BCA</p>
                        <p><strong>Account Number:</strong> 1234567890</p>
                        <p><strong>Account Name:</strong> {{ $company['name'] }}</p>
                    @endif

                    <p style="margin-top: 10px;"><em>Please include invoice number in transfer description</em></p>
                </div>
            </td>
            <td style="width: 45%; vertical-align: top;">
                <table class="totals-table">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Tax ({{ $tax_rate ?? setting('tax_rate', 11) }}%)</td>
                        <td class="text-right">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Shipping Cost</td>
                        <td class="text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    @if (($order->discount_amount ?? 0) > 0)
                        <tr>
                            <td>Discount</td>
                            <td class="text-right" style="color: #dc3545;">- Rp
                                {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td>GRAND TOTAL</td>
                        <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p class="thank-you">Thank you for your order!</p>
        <p>{{ $company['name'] }}</p>
        <p>{{ $company['address'] }}</p>
        <p>{{ $company['email'] }} | {{ $company['phone'] }}</p>
    </div>
</body>

</html>


