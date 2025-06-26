<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير المريض</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            direction: rtl;
            text-align: right;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background-color: #f0f0f0;
        }
        td, th {
            padding: 8px;
        }
    </style>
</head>
<body>
    <h2>Patient's medical report</h2>

    <p dir="ltr"><strong>Name:</strong> {{ $patient->Name }}</p>
    <p dir="ltr"><strong>Date of birth:</strong> {{ $patient->Date_Of_Birth }}</p>

    <br>
    <h4>Analysis results:</h4>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>ALB</th>
                <th>ALP</th>
                <th>ALT</th>
                <th>AST</th>
                <th>BIL</th>
                <th>CHE</th>
                <th>CHOL</th>
                <th>CREA</th>
                <th>GGT</th>
                <th>PROT</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
        <tr>
            <td>{{ $record->created_at->format('Y-m-d') }}</td>
            <td>{{ $record->ALB }}</td>
            <td>{{ $record->ALP }}</td>
            <td>{{ $record->ALT }}</td>
            <td>{{ $record->AST }}</td>
            <td>{{ $record->BIL }}</td>
            <td>{{ $record->CHE }}</td>
            <td>{{ $record->CHOL }}</td>
            <td>{{ $record->CREA }}</td>
            <td>{{ $record->GGT }}</td>
            <td>{{ $record->PROT }}</td>
            <td>{{ $record->prediction->result ?? '—' }}</td>
        </tr>
        @empty
            <tr>
                <td colspan="6">لا توجد تحاليل مسجلة</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
