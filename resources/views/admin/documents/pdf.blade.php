<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $template->name }} - {{ $student->admission_no }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 16px;
            line-height: 1.6;
            margin: 40px;
        }
        .watermark {
            position: absolute;
            top: 30%;
            left: 20%;
            font-size: 80px;
            color: rgba(0,0,0,0.05);
            transform: rotate(-45deg);
            z-index: -1;
        }
        .header-info {
            display: table;
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .doc-no {
            display: table-cell;
            text-align: left;
            font-weight: bold;
        }
        .date {
            display: table-cell;
            text-align: right;
            font-weight: bold;
        }
        .content {
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ config('app.school_name', 'MKhan School') }}</div>
    
    <div class="header-info">
        <div class="doc-no">Ref No: {{ $document->document_no }}</div>
        <div class="date">Date: {{ $issued_at }}</div>
    </div>

    <div class="content">
        {!! $content !!}
    </div>
</body>
</html>
