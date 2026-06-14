<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
    .header { text-align: center; border-bottom: 2px solid #000666; padding-bottom: 10px; margin-bottom: 15px; }
    .header h2 { color: #000666; margin: 0; font-size: 18px; }
    .header p { margin: 3px 0; font-size: 11px; }
    .title { background: #000666; color: #fff; text-align: center; padding: 8px; font-size: 14px; font-weight: bold; margin-bottom: 15px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 7px 10px; border-bottom: 1px solid #eee; }
    td:first-child { font-weight: bold; color: #555; width: 45%; }
    .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    .amount-box { background: #f0f8e8; border: 1px solid #1cc88a; padding: 12px; text-align: center; margin: 15px 0; border-radius: 4px; }
    .amount-box .amount { font-size: 22px; font-weight: bold; color: #1a7a4a; }
</style>
</head>
<body>
<div class="header">
    <h2>{{ $school->name ?? 'School' }}</h2>
    <p>{{ $school->address ?? '' }}</p>
</div>

<div class="title">FEE PAYMENT RECEIPT</div>

<table>
    <tr><td>Receipt No:</td><td><strong>{{ $fee->transaction_id ?? 'N/A' }}</strong></td></tr>
    <tr><td>Date:</td><td>{{ \Carbon\Carbon::parse($fee->payment_date)->format('d M Y, h:i A') }}</td></tr>
    <tr><td>Student Name:</td><td>{{ $student->first_name }} {{ $student->last_name }}</td></tr>
    <tr><td>Admission No:</td><td>{{ $student->admission_no }}</td></tr>
    <tr><td>Class:</td><td>{{ $student->currentClass->name ?? '—' }} {{ $student->currentSection->name ?? '' }}</td></tr>
    <tr><td>Challan No:</td><td>{{ $fee->challan_no ?? '—' }}</td></tr>
    <tr><td>Fee Type:</td><td>{{ $fee->fee_category ?? '—' }}</td></tr>
    <tr><td>Due Date:</td><td>{{ $fee->due_date ?? '—' }}</td></tr>
    <tr><td>Payment Method:</td><td>{{ $fee->payment_method ?? 'Cash' }}</td></tr>
</table>

<div class="amount-box">
    <div style="color:#555; margin-bottom:4px;">Amount Paid</div>
    <div class="amount">PKR {{ number_format($fee->amount, 2) }}</div>
</div>

<div class="footer">
    This is a computer-generated receipt. No signature required.<br>
    {{ $school->name ?? '' }} — {{ now()->format('Y') }}
</div>
</body>
</html>
