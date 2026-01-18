<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Inventory Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #6F4E37;
        }

        .header h1 {
            font-size: 18px;
            color: #6F4E37;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 6px 4px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #6F4E37;
            color: white;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            font-size: 7px;
            border-radius: 3px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .low-stock {
            background-color: #fff3cd !important;
        }

        .out-of-stock {
            background-color: #f8d7da !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ setting('company_name', 'Eureka Kopi') }}</h1>
        <p>Inventory Report</p>
        <p>Generated: {{ now()->format('d M Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">SKU</th>
                <th style="width: 18%;">Product Name</th>
                <th style="width: 10%;">Category</th>
                <th style="width: 8%;">Type</th>
                <th style="width: 6%;" class="text-right">Weight</th>
                <th style="width: 10%;" class="text-right">Cost Price</th>
                <th style="width: 10%;" class="text-right">Sell Price</th>
                <th style="width: 7%;" class="text-center">Stock</th>
                <th style="width: 7%;" class="text-center">Reserved</th>
                <th style="width: 7%;" class="text-center">Available</th>
                <th style="width: 6%;" class="text-center">Min</th>
                <th style="width: 8%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                @php
                    $inventory = $product->inventory;
                    $available = $inventory ? $inventory->available : 0;
                    $status = 'OK';
                    $rowClass = '';

                    if (!$inventory || $available <= 0) {
                        $status = 'Out of Stock';
                        $rowClass = 'out-of-stock';
                    } elseif ($available <= $product->min_stock) {
                        $status = 'Low Stock';
                        $rowClass = 'low-stock';
                    }
                @endphp
                <tr class="{{ $rowClass }}">
                    <td>{{ $product->sku }}</td>
                    <td>{{ Str::limit($product->name, 30) }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $product->type)) }}</td>
                    <td class="text-right">{{ $product->weight }}g</td>
                    <td class="text-right">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $inventory ? $inventory->quantity : 0 }}</td>
                    <td class="text-center">{{ $inventory ? $inventory->reserved : 0 }}</td>
                    <td class="text-center"><strong>{{ $available }}</strong></td>
                    <td class="text-center">{{ $product->min_stock }}</td>
                    <td class="text-center">
                        @if ($status == 'OK')
                            <span class="badge badge-success">OK</span>
                        @elseif($status == 'Low Stock')
                            <span class="badge badge-warning">Low</span>
                        @else
                            <span class="badge badge-danger">Out</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">No products found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i:s') }} | {{ setting('company_name', 'Eureka Kopi') }}</p>
        <p>{{ setting('company_address', '') }} | {{ setting('company_email', '') }} |
            {{ setting('company_phone', '') }}</p>
    </div>
</body>

</html>


