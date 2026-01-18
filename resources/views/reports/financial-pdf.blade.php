<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Financial Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
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

        .summary {
            margin-bottom: 20px;
        }

        .summary-row {
            display: inline-block;
            width: 30%;
            text-align: center;
            padding: 10px;
            margin-right: 2%;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .summary-row:last-child {
            margin-right: 0;
        }

        .summary-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }

        .summary-value.income {
            color: #28a745;
        }

        .summary-value.expense {
            color: #dc3545;
        }

        .summary-value.net {
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 8px 6px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #6F4E37;
            color: white;
            font-weight: bold;
            font-size: 9px;
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
            padding: 2px 6px;
            font-size: 8px;
            border-radius: 3px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
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

        .amount-income {
            color: #28a745;
            font-weight: bold;
        }

        .amount-expense {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ setting('company_name', 'Eureka Kopi') }}</h1>
        <p>Financial Report</p>
        <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        @if ($category)
            <p>Category: {{ ucfirst($category) }}</p>
        @endif
    </div>

    <div class="summary">
        <table style="width: 100%;">
            <tr>
                <td style="width: 33%; text-align: center; background: #d4edda; padding: 15px; border-radius: 5px;">
                    <div class="summary-label">Total Income</div>
                    <div class="summary-value income">Rp {{ number_format($totals['income'], 0, ',', '.') }}</div>
                </td>
                <td style="width: 33%; text-align: center; background: #f8d7da; padding: 15px; border-radius: 5px;">
                    <div class="summary-label">Total Expense</div>
                    <div class="summary-value expense">Rp {{ number_format($totals['expense'], 0, ',', '.') }}</div>
                </td>
                <td style="width: 33%; text-align: center; background: #cce5ff; padding: 15px; border-radius: 5px;">
                    <div class="summary-label">Net Profit/Loss</div>
                    <div class="summary-value net">Rp {{ number_format($totals['net'], 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Date</th>
                <th style="width: 10%;">Type</th>
                <th style="width: 12%;">Category</th>
                <th style="width: 30%;">Description</th>
                <th style="width: 15%;" class="text-right">Amount</th>
                <th style="width: 12%;">Created By</th>
                <th style="width: 11%;">Reference</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->transaction_date->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge {{ $log->type == 'income' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($log->type) }}
                        </span>
                    </td>
                    <td>{{ $log->category_display }}</td>
                    <td>{{ Str::limit($log->description, 50) }}</td>
                    <td class="text-right {{ $log->type == 'income' ? 'amount-income' : 'amount-expense' }}">
                        {{ $log->type == 'income' ? '+' : '-' }} Rp {{ number_format($log->amount, 0, ',', '.') }}
                    </td>
                    <td>{{ $log->creator->name ?? '-' }}</td>
                    <td>
                        @if ($log->reference_type)
                            {{ class_basename($log->reference_type) }} #{{ $log->reference_id }}
                        @else
                            Manual
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No records found</td>
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


