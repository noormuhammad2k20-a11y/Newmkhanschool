<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $template->name }} - {{ $student->admission_no ?? '' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 12mm 15mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a2a4a;
            background: #ffffff;
        }
    </style>
</head>
<body>
    {!! $content !!}
</body>
</html>
