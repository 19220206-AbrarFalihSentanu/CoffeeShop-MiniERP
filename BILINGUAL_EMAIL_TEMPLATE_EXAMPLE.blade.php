{{-- File: resources/views/emails/order-created.blade.php (BILINGUAL VERSION) --}}
{{-- Email untuk konfirmasi order baru (untuk customer dan admin) --}}

{{-- IMPORTANT: Set locale untuk email ini berdasarkan user preference --}}
@php
    // Jika adalah customer order, gunakan customer's locale preference
// Jika adalah admin notification, gunakan default locale
if ($recipientType === 'customer' && auth('web')->check()) {
        $locale = $order->user->locale ?? app()->getLocale();
    } else {
        $locale = app()->getLocale(); // Default to app locale for admin
    }

    // Untuk mutable messages atau jika perlu override:
    // App::setLocale($locale);

@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ trans('emails.' . ($recipientType === 'customer' ? 'order_created_title' : 'order_created_title'), [], null, $locale) }}
    </title>
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
            background-color: #696cff;
            color: white;
        }

        .text-right {
            text-align: right;
        }

        .summary-table {
            width: 100%;
            margin: 20px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 16px;
            color: #696cff;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ trans('emails.order_created_title', [], null, $locale) }}</h1>
    </div>

    <div class="content">
        {{-- Greeting --}}
        <p>{{ trans('emails.hello', [], null, $locale) }},</p>

        @if ($recipientType === 'customer')
            <p>{{ trans('emails.thank_you_for_order', [], null, $locale) }}.</p>
            <div class="info-banner">
                <h3>{{ trans('emails.order_received', [], null, $locale) }}</h3>
                <p>{{ trans('emails.order_status', [], null, $locale) }}:
                    <strong>{{ trans('general.pending', [], null, $locale) }}</strong></p>
            </div>
        @else
            <p>{{ trans('emails.order_received', [], null, $locale) }}.</p>
            <div class="admin-banner">
                <h3>🔔 {{ trans('emails.order_number', [], null, $locale) }}: #{{ $order->order_number }}</h3>
                <p>{{ trans('general.customer_name', [], null, $locale) }}: {{ $order->user->name }}</p>
            </div>
        @endif

        {{-- Order Information --}}
        <div class="order-info">
            <h3>{{ trans('emails.order_number', [], null, $locale) }}</h3>
            <p><strong>#{{ $order->order_number }}</strong></p>

            <h3 style="margin-top: 20px;">{{ trans('emails.order_date', [], null, $locale) }}</h3>
            <p>{{ $order->created_at->format('d F Y H:i') }}</p>

            <h3 style="margin-top: 20px;">{{ trans('general.address', [], null, $locale) }}</h3>
            <p>{{ $order->shipping_address ?: $order->user->address }}</p>
        </div>

        {{-- Order Items --}}
        <h3>{{ trans('emails.order_items', [], null, $locale) }}</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>{{ trans('emails.product_name', [], null, $locale) }}</th>
                    <th class="text-right">{{ trans('emails.quantity', [], null, $locale) }}</th>
                    <th class="text-right">{{ trans('emails.price', [], null, $locale) }}</th>
                    <th class="text-right">{{ trans('emails.subtotal', [], null, $locale) }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Order Summary --}}
        <div class="summary-table">
            <div class="summary-row">
                <span>{{ trans('emails.subtotal', [], null, $locale) }}</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>

            @if ($order->tax_amount > 0)
                <div class="summary-row">
                    <span>{{ trans('general.tax', [], null, $locale) }} ({{ $order->tax_rate }}%)</span>
                    <span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                </div>
            @endif

            @if ($order->shipping_cost > 0)
                <div class="summary-row">
                    <span>{{ trans('orders.shipping_cost', [], null, $locale) }}</span>
                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="summary-row total">
                <span>{{ trans('emails.total', [], null, $locale) }}</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Call to Action --}}
        @if ($recipientType === 'customer')
            <div style="text-align: center;">
                <p>{{ trans('emails.please_upload_proof', [], null, $locale) }}</p>
                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn">
                    {{ trans('emails.view_order', [], null, $locale) }}
                </a>
            </div>

            <div class="info-banner">
                <h3>{{ trans('emails.payment_status', [], null, $locale) }}</h3>
                <p>{{ trans('emails.awaiting_payment', [], null, $locale) }}</p>
                <p><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></p>
            </div>
        @else
            <div style="text-align: center;">
                <a href="{{ route('owner.orders.show', $order->id) }}" class="btn">
                    {{ trans('emails.view_details', [], null, $locale) }}
                </a>
            </div>
        @endif

        {{-- Notes --}}
        @if ($order->notes)
            <div class="order-info">
                <h3>{{ trans('emails.note', [], null, $locale) }}</h3>
                <p>{{ $order->notes }}</p>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>{{ trans('emails.thank_you', [], null, $locale) }}</p>
        <p>&copy; {{ date('Y') }} {{ setting('company_name', 'Eureka Kopi') }}.
            {{ trans('emails.contact_support', [], null, $locale) }}: {{ setting('company_email') }}</p>
        <p><small>{{ trans('emails.important_notice', [], null, $locale) }}:
                {{ trans('general.email_notice', [], null, $locale) }}</small></p>
    </div>
</body>

</html>

{{-- 
NOTES UNTUK IMPLEMENTASI:
1. Gunakan trans() helper untuk multi-language support
2. Parameter ketiga ($locale) memastikan email menggunakan bahasa yang tepat
3. Pastikan semua lang keys ada di lang/id/emails.php dan lang/en/emails.php
4. Untuk order dengan customer, gunakan customer's locale preference
5. Untuk admin notification, gunakan default app locale
6. Test email dengan kedua bahasa sebelum production
--}}
