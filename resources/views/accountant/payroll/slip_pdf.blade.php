<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Salary Slip - {{ $payroll->emp_id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .school-name { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .title { font-size: 18px; text-decoration: underline; }
        .details-table { width: 100%; margin-bottom: 20px; }
        .details-table td { padding: 5px; }
        .salary-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .salary-table th, .salary-table td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        .salary-table th { background-color: #f5f5f5; }
        .total-row { font-weight: bold; }
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #666; }
        .signature { margin-top: 50px; display: flex; justify-content: space-between; }
        .signature div { border-top: 1px solid #000; padding-top: 5px; width: 200px; text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <div class="school-name">{{ $payroll->teacher->school->name ?? setting('general.organization_name', 'Galaxy Academy') }}</div>
    <div class="title">Salary Slip for {{ \Carbon\Carbon::parse($payroll->month_year)->format('F Y') }}</div>
</div>

<table class="details-table">
    <tr>
        <td><strong>Employee ID:</strong> {{ $payroll->emp_id }}</td>
        <td><strong>Name:</strong> {{ $payroll->name }}</td>
    </tr>
    <tr>
        <td><strong>Role:</strong> {{ $payroll->role }}</td>
        <td><strong>Status:</strong> {{ $payroll->status }}</td>
    </tr>
</table>

<table class="salary-table">
    <thead>
        <tr>
            <th>Description</th>
            <th>Amount (PKR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Basic Salary</td>
            <td>{{ number_format($payroll->basic_pay, 2) }}</td>
        </tr>
        <tr>
            <td>Allowances</td>
            <td>{{ number_format($payroll->allowances, 2) }}</td>
        </tr>
        <tr>
            <td>Deductions</td>
            <td>{{ number_format($payroll->deductions, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td>Net Salary</td>
            <td>{{ number_format($payroll->net_salary, 2) }}</td>
        </tr>
    </tbody>
</table>

<div class="signature" style="margin-top: 100px;">
    <div style="float: left; border-top: 1px solid #000; width: 200px; text-align: center;">Accountant Signature</div>
    <div style="float: right; border-top: 1px solid #000; width: 200px; text-align: center;">Employee Signature</div>
    <div style="clear: both;"></div>
</div>

<div class="footer">
    This is an automatically generated document. No signature is required.
</div>

</body>
</html>
