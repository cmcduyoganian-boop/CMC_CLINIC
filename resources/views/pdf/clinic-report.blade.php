<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Clinic Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 10px; color: #000; }
        .header { text-align: center; margin-bottom: 16px; }
        .header h1 { font-size: 18px; margin-bottom: 4px; }
        .header p { font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f2f2f2; font-weight: 700; text-transform: uppercase; }
        .text-list { white-space: nowrap; }
        tfoot td { background: #f2f2f2; font-weight: 700; border-top: 2px solid #000; border-bottom: 2px solid #000; }
        .footer { text-align: center; margin-top: 16px; font-size: 9px; color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Clinic Report - {{ ucfirst($reportType) }}</h1>
        <p>Period: {{ $startDate }} to {{ $endDate }} | Generated: {{ now()->format('F d, Y H:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Male</th>
                <th>Female</th>
                <th>BSIS I</th>
                <th>BSIS II</th>
                <th>BSIS III</th>
                <th>BSIS IV</th>
                <th>Faculty/Admin</th>
                <th>Carmenanon</th>
                <th>Non-Carmenanon</th>
                <th>S & S</th>
                <th>Dispensed Medications/Supplies</th>
                <th>Services</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportRows as $row)
                <tr>
                    <td>{{ $row['date_label'] }}</td>
                    <td>{{ $row['male'] }}</td>
                    <td>{{ $row['female'] }}</td>
                    <td>{{ $row['bsis1'] }}</td>
                    <td>{{ $row['bsis2'] }}</td>
                    <td>{{ $row['bsis3'] }}</td>
                    <td>{{ $row['bsis4'] }}</td>
                    <td>{{ $row['faculty_admin'] }}</td>
                    <td>{{ $row['carmenanon'] }}</td>
                    <td>{{ $row['non_carmenanon'] }}</td>
                    <td class="text-list">{{ $row['complaints'] }}</td>
                    <td class="text-list">{{ $row['medicines'] }}</td>
                    <td class="text-list">{{ $row['services'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Grand Total</td>
                <td>{{ $grandTotals['male'] }}</td>
                <td>{{ $grandTotals['female'] }}</td>
                <td>{{ $grandTotals['bsis1'] }}</td>
                <td>{{ $grandTotals['bsis2'] }}</td>
                <td>{{ $grandTotals['bsis3'] }}</td>
                <td>{{ $grandTotals['bsis4'] }}</td>
                <td>{{ $grandTotals['faculty_admin'] }}</td>
                <td>{{ $grandTotals['carmenanon'] }}</td>
                <td>{{ $grandTotals['non_carmenanon'] }}</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Report generated from CMC Clinic Management System | {{ now()->format('F d, Y H:i A') }}</p>
    </div>
</body>
</html>
