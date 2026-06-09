<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Challan - {{ $challan_no }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; }
        .wrapper { width: 100%; border: 1px solid #ccc; padding: 20px; box-sizing: border-box; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 2px 0; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        .fee-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .fee-table th, .fee-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .fee-table th { background-color: #f0f0f0; }
        .total-row { font-weight: bold; }
        .footer { margin-top: 30px; display: table; width: 100%; }
        .qr-code { display: table-cell; width: 20%; vertical-align: bottom; }
        .signatures { display: table-cell; width: 80%; text-align: right; vertical-align: bottom; }
        .sig-box { display: inline-block; width: 150px; border-top: 1px solid #000; text-align: center; margin-left: 30px; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>{{ $school['name'] }}</h1>
            <p>{{ $school['address'] }} | Ph: {{ $school['phone'] }}</p>
            <h2 style="margin:10px 0 0 0;">FEE CHALLAN</h2>
        </div>

        <table class="info-table">
            <tr>
                <td><strong>Challan No:</strong> {{ $challan_no }}</td>
                <td><strong>Issue Date:</strong> {{ $issued_date }}</td>
                <td><strong>Due Date:</strong> {{ $due_date }}</td>
            </tr>
            <tr>
                <td><strong>Student Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</td>
                <td><strong>Admission No:</strong> {{ $student->admission_no }}</td>
                <td><strong>Class:</strong> {{ $student->currentClass->name ?? 'N/A' }}</td>
            </tr>
        </table>

        <table class="fee-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Fee Type / Month</th>
                    <th style="text-align: right;">Amount (PKR)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fees as $index => $fee)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $fee->feeType->name ?? 'Tuition Fee' }} - {{ \Carbon\Carbon::parse($fee->due_date)->format('F Y') }}</td>
                    <td style="text-align: right;">{{ number_format($fee->amount, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">Total Amount Due:</td>
                    <td style="text-align: right;">{{ number_format($total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-bottom: 20px;">
            <strong>Amount in words:</strong> 
            {{ (new \NumberFormatter("en", \NumberFormatter::SPELLOUT))->format($total_amount) }} Rupees Only
        </div>

        <div class="footer">
            <div class="qr-code">
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($qr_data)) !!} ">
            </div>
            <div class="signatures">
                <div class="sig-box">Cashier Sign</div>
                <div class="sig-box">Bank Officer Sign</div>
            </div>
        </div>
        
        <p style="text-align: center; font-size: 10px; margin-top: 20px; border-top: 1px dashed #ccc; padding-top:10px;">
            Note: Please deposit this challan at any branch of XYZ Bank before the due date to avoid late payment charges.
        </p>
    </div>
</body>
</html>
