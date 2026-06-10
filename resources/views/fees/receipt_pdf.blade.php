<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Receipt - {{ $fee->challan_no }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #334155;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            font-size: 13px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }
        .company-details {
            font-size: 12px;
            color: #64748b;
        }
        .receipt-title {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            text-align: right;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .receipt-meta {
            text-align: right;
            font-size: 12px;
        }
        .receipt-meta strong {
            color: #0f172a;
        }
        .divider {
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .info-table td {
            vertical-align: top;
            width: 50%;
        }
        .info-box {
            background-color: #f8fafc;
            border-radius: 6px;
            padding: 12px;
            border: 1px solid #e2e8f0;
        }
        .info-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 4px;
            font-weight: bold;
        }
        .info-value {
            font-size: 14px;
            color: #0f172a;
            font-weight: bold;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #cbd5e1;
            font-weight: bold;
        }
        .details-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
            color: #0f172a;
        }
        .details-table th.amount, .details-table td.amount {
            text-align: right;
        }
        .totals-wrapper {
            width: 100%;
        }
        .totals-table {
            width: 280px;
            float: right;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 10px;
            font-size: 13px;
        }
        .totals-table .label {
            color: #64748b;
            text-align: right;
        }
        .totals-table .value {
            text-align: right;
            font-weight: bold;
            color: #0f172a;
        }
        .totals-table .total-row td {
            background-color: #f8fafc;
            border-top: 2px solid #cbd5e1;
            border-bottom: 2px solid #cbd5e1;
            padding: 12px 10px;
        }
        .totals-table .total-row .label, .totals-table .total-row .value {
            font-size: 15px;
            color: #2563eb;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 4px;
        }
        .status-paid {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-pending {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .status-overdue {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            clear: both;
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <h1 class="company-name">School Management System</h1>
                <div class="company-details">
                    123 Education Street, Learning City<br>
                    Phone: +1 234 567 8900<br>
                    Email: finance@school.edu
                </div>
            </td>
            <td style="text-align: right;">
                <h1 class="receipt-title">RECEIPT</h1>
                <div class="receipt-meta">
                    <strong>Receipt No:</strong> {{ $fee->challan_no }}<br>
                    <strong>Issue Date:</strong> {{ date('d M Y') }}<br>
                </div>
                <div style="margin-top: 10px;">
                    @if($fee->status === 'Paid')
                        <span class="status-badge status-paid">PAID</span>
                    @elseif($fee->status === 'Overdue')
                        <span class="status-badge status-overdue">OVERDUE</span>
                    @else
                        <span class="status-badge status-pending">PENDING</span>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td style="padding-right: 8px;">
                <div class="info-box">
                    <div class="info-title">Billed To</div>
                    <div class="info-value">{{ $fee->student->user->name ?? ($fee->student->first_name . ' ' . $fee->student->last_name) }}</div>
                    <div style="font-size: 12px; margin-top: 4px; color: #475569;">
                        <strong>Admission No:</strong> {{ $fee->student->admission_no }}<br>
                        <strong>Class:</strong> {{ $fee->student->class->name ?? 'Standard Class' }}
                    </div>
                </div>
            </td>
            <td style="padding-left: 8px;">
                <div class="info-box">
                    <div class="info-title">Payment Information</div>
                    <div style="font-size: 12px; margin-top: 4px; color: #475569;">
                        <strong>Fee Category:</strong> {{ $fee->fee_category }}<br>
                        <strong>Due Date:</strong> {{ \Carbon\Carbon::parse($fee->due_date)->format('d M Y') }}<br>
                        @if($fee->paid_date)
                        <strong>Paid On:</strong> {{ \Carbon\Carbon::parse($fee->paid_date)->format('d M Y') }}
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table class="details-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $fee->fee_category }} (Base Fee)</td>
                <td class="amount">Rs. {{ number_format($fee->amount, 2) }}</td>
            </tr>
            @if($fee->fine > 0)
            <tr>
                <td>Late Fee / Fine</td>
                <td class="amount">Rs. {{ number_format($fee->fine, 2) }}</td>
            </tr>
            @endif
            @if($fee->discount > 0)
            <tr>
                <td>Discount / Scholarship</td>
                <td class="amount">- Rs. {{ number_format($fee->discount, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="totals-wrapper">
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal:</td>
                <td class="value">Rs. {{ number_format($fee->amount + $fee->fine - $fee->discount, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Amount Paid:</td>
                <td class="value">Rs. {{ number_format($fee->paid_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td class="label">Balance Due:</td>
                <td class="value">Rs. {{ number_format(max(0, ($fee->amount + $fee->fine - $fee->discount) - $fee->paid_amount), 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <strong>Thank you for your payment!</strong><br>
        This is a computer-generated receipt and does not require a physical signature.
    </div>

</body>
</html>
