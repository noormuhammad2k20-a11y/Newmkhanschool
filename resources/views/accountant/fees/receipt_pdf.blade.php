<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Official Fee Receipt - {{ $fee->challan_no }}</title>
<style>
@page { margin: 8mm; size: A4 portrait; }
* { box-sizing: border-box; }
body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #000; line-height: 1.3; background: #fff; margin: 0; padding: 0; }
.receipt-wrapper { 
    border: 2px solid #000; 
    padding: 8px; 
    margin-bottom: 15px; 
    position: relative; 
    page-break-inside: avoid;
    height: 85mm; /* Fits exactly 3 on A4 */
    overflow: hidden;
    background: #fff;
}
.watermark-layer {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: 0;
    overflow: hidden;
    pointer-events: none;
}
.wm-text {
    position: absolute;
    font-size: 6px;
    font-weight: bold;
    color: #ebebeb; /* Extremely light grey, safe for all PDF engines */
    white-space: nowrap;
    font-family: 'Arial', sans-serif;
}
.inner-border {
    border: 1px solid #777;
    padding: 8px 12px;
    height: 100%;
    position: relative;
    z-index: 1;
}
.micro-text-border {
    position: absolute;
    top: 2px; left: 2px; right: 2px; bottom: 2px;
    border: 1px dashed #aaa;
    pointer-events: none;
    z-index: 1;
}
.school-header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 6px; }
.school-name { font-size: 15px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin: 0; color: #000; }
.school-address { font-size: 8px; color: #333; margin: 2px 0; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; }
.receipt-title { font-size: 9px; font-weight: 900; background: #000; color: #fff; padding: 3px 15px; display: inline-block; margin-top: 4px; text-transform: uppercase; letter-spacing: 3px; }
.info-table { width: 100%; margin-bottom: 4px; border-collapse: collapse; }
.info-table td { padding: 3px 2px; font-size: 9px; border-bottom: 1px solid #f0f0f0; }
.info-table td.label { font-weight: bold; color: #555; width: 15%; text-transform: uppercase; }
.info-table td.value { font-weight: bold; width: 35%; color: #000; }
.fee-table { width: 100%; border-collapse: collapse; margin-top: 6px; margin-bottom: 6px; border: 1.5px solid #000; }
.fee-table th { border-bottom: 1.5px solid #000; background: rgba(0,0,0,0.05); text-align: left; padding: 4px; font-size: 8px; text-transform: uppercase; letter-spacing: 1px; color: #000; }
.fee-table td { padding: 4px; font-size: 9.5px; border-bottom: 1px solid #ccc; }
.fee-table th.right, .fee-table td.right { text-align: right; }
.fee-table .bold td { font-weight: bold; color: #000; }
.fee-table .total-row td { border-top: 1.5px solid #000; border-bottom: none; padding-top: 5px; font-size: 10px; background: rgba(0,0,0,0.02); }
.status-stamp { position: absolute; top: 40%; right: 8%; transform: rotate(-15deg); font-size: 40px; font-weight: 900; opacity: 0.6; padding: 5px 20px; border: 4px solid; border-radius: 8px; text-transform: uppercase; letter-spacing: 5px; z-index: 2; pointer-events: none; }
.status-stamp.paid { color: #166534; border-color: #166534; }
.status-stamp.partial { color: #b45309; border-color: #b45309; }
.status-stamp.unpaid { color: #991b1b; border-color: #991b1b; }
.footer-table { width: 100%; margin-top: 10px; }
.footer-table td { text-align: center; vertical-align: bottom; height: 35px; font-size: 8.5px; font-weight: bold; color: #222; text-transform: uppercase; }
.signature-line { border-top: 1px solid #000; width: 140px; display: inline-block; margin-bottom: 4px; }
.footer-note { text-align: center; font-size: 7px; color: #000; margin-top: 10px; border-top: 1.5px solid #000; padding-top: 3px; font-family: monospace; letter-spacing: 1px; font-weight: bold; }
.micro-footer { font-size: 5.5px; text-align: justify; text-transform: uppercase; color: #666; margin-top: 3px; line-height: 1.2; font-family: monospace; }
</style>
</head>
<body>
<div class="receipt-wrapper">
    <!-- NATIVE DOM WATERMARK LAYER FOR DOMPDF COMPATIBILITY -->
    <div class="watermark-layer">
        @php
            $wmTexts = ['GALAXY ACADEMY', 'PAID', 'CONFIDENTIAL', 'COMPUTER GENERATED'];
            $wmAngles = [-20, 15, -10, 25];
        @endphp
        @for($row = 0; $row < 25; $row++)
            @for($col = 0; $col < 22; $col++)
                @php 
                    $idx = ($row + $col) % 4;
                    $text = $wmTexts[$idx];
                    $angle = $wmAngles[$idx];
                    $top = ($row * 4.2) - 2; 
                    $left = ($col * 4.8) - 2;
                @endphp
                <div class="wm-text" style="top: {{ $top }}%; left: {{ $left }}%; transform: rotate({{ $angle }}deg);">{{ $text }}</div>
            @endfor
        @endfor
    </div>

    <div class="micro-text-border"></div>
    <div class="inner-border">
        <!-- Status Stamp -->
        @if($fee->status == 'Paid')
            <div class="status-stamp paid">CLEARED</div>
        @elseif($fee->status == 'Partial')
            <div class="status-stamp partial">PARTIAL</div>
        @endif

        <div class="school-header">
            @if(file_exists(public_path('logo.png')))
                <img src="{{ public_path('logo.png') }}" style="height: 45px; margin-bottom: 4px;" alt="Galaxy Coaching Academy">
            @elseif(file_exists(public_path('images/logo.png')))
                <img src="{{ public_path('images/logo.png') }}" style="height: 45px; margin-bottom: 4px;" alt="Galaxy Coaching Academy">
            @else
                <h1 class="school-name">Galaxy Coaching Academy</h1>
            @endif
            <p class="school-address">Umerkot, Sindh, Pakistan &nbsp;•&nbsp; Official Financial Record</p>
            <div class="receipt-title">FEE PAYMENT RECEIPT</div>
        </div>

        <table class="info-table">
            <tr>
                <td class="label">Receipt No:</td>
                <td class="value">{{ $fee->challan_no }}</td>
                <td class="label">Transaction Date:</td>
                <td class="value">{{ date('d M Y, h:i A') }}</td>
            </tr>
            <tr>
                <td class="label">Student Name:</td>
                <td class="value">{{ strtoupper($fee->student->first_name . ' ' . $fee->student->last_name) }}</td>
                <td class="label">Class/Section:</td>
                <td class="value">{{ strtoupper($fee->student->currentClass->name ?? '') }} {{ $fee->student->currentSection ? '- '.strtoupper($fee->student->currentSection->name) : '' }}</td>
            </tr>
            <tr>
                <td class="label">Fee Category:</td>
                <td class="value">{{ strtoupper($fee->category->name ?? $fee->fee_category) }}</td>
                <td class="label">Due Date:</td>
                <td class="value">{{ \Carbon\Carbon::parse($fee->due_date)->format('d M Y') }}</td>
            </tr>
        </table>

        <table class="fee-table">
            <tr>
                <th>Description</th>
                <th class="right">Amount (PKR)</th>
            </tr>
            <tr>
                <td>BASE FEE AMOUNT</td>
                <td class="right">{{ number_format($fee->amount, 2) }}</td>
            </tr>
            @if($fee->discount > 0)
            <tr>
                <td>DISCOUNT APPLIED</td>
                <td class="right">-{{ number_format($fee->discount, 2) }}</td>
            </tr>
            @endif
            @if($fee->fine > 0)
            <tr>
                <td>LATE PAYMENT FINE</td>
                <td class="right">{{ number_format($fee->fine, 2) }}</td>
            </tr>
            @endif
            <tr class="bold total-row">
                <td>TOTAL PAYABLE</td>
                <td class="right">{{ number_format($fee->amount - $fee->discount + $fee->fine, 2) }}</td>
            </tr>
            <tr class="bold">
                <td>AMOUNT PAID</td>
                <td class="right">{{ number_format($fee->paid_amount, 2) }}</td>
            </tr>
            <tr class="bold">
                <td>BALANCE DUE</td>
                <td class="right">{{ number_format(max(0, $fee->amount - $fee->discount + $fee->fine - $fee->paid_amount), 2) }}</td>
            </tr>
        </table>

        <table class="footer-table">
            <tr>
                <td width="38%">
                    <div style="font-size: 7px; color: #444; text-align: left; padding-left: 5px; font-family: monospace; line-height: 1.4;">
                        <strong>STATUS:</strong> {{ strtoupper($fee->status) }}<br>
                        <strong>ISSUER ID:</strong> AUTH-{{ auth()->id() ?? 'SYS' }}<br>
                        <strong>VERIFICATION CODE:</strong> {{ strtoupper(substr(md5($fee->id . $fee->challan_no . time()), 0, 10)) }}
                    </div>
                </td>
                <td width="31%">
                    <div class="signature-line"></div><br>
                    AUTHORIZED CASHIER
                </td>
                <td width="31%">
                    <div class="signature-line"></div><br>
                    PRINCIPAL / OFFICIAL STAMP
                </td>
            </tr>
        </table>

        <div class="footer-note">*** COMPUTER GENERATED OFFICIAL RECEIPT — DO NOT DUPLICATE ***</div>
        <div class="micro-footer">
            GALAXY ACADEMY FINANCIAL SYSTEM • DOC REF: {{ $fee->challan_no }} • GENERATED ON: {{ date('Y-m-d H:i:s') }} • THIS DOCUMENT IS INVALID IF ALTERED. THE BACKGROUND CONTAINS A REPEATING WATERMARK FOR SECURITY PURPOSES. ANY DISCREPANCY MUST BE REPORTED WITHIN 24 HOURS.
        </div>
    </div>
</div>
</body>
</html>
