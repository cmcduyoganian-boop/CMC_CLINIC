<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Clinic Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 9px; color: #000; }
        .header { text-align: center; margin-bottom: 12px; }
        .header h1 { font-size: 16px; margin-bottom: 4px; }
        .header p { font-size: 9px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; table-layout: fixed; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; vertical-align: top; word-wrap: break-word; white-space: normal; overflow-wrap: break-word; }
        th { background: #f2f2f2; font-weight: 700; text-transform: uppercase; }
        tfoot td { background: #f2f2f2; font-weight: 700; border-top: 2px solid #000; border-bottom: 2px solid #000; }
        .footer { text-align: center; margin-top: 12px; font-size: 8px; color: #555; }

        .col-date { width: 7%; }
        .col-male { width: 4%; }
        .col-female { width: 5%; }
        .col-bsis1 { width: 5%; }
        .col-bsis2 { width: 5%; }
        .col-bsis3 { width: 5%; }
        .col-bsis4 { width: 5%; }
        .col-faculty { width: 6%; }
        .col-carmenanon { width: 6%; }
        .col-non-carmenanon { width: 7%; }
        .col-complaints { width: 14%; }
        .col-medicines { width: 16%; }
        .col-services { width: 16%; }
        .col-grand-total { width: 4%; }
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
                <th class="col-date">Date</th>
                <th class="col-male">Male</th>
                <th class="col-female">Female</th>
                <th class="col-bsis1">BSIS I</th>
                <th class="col-bsis2">BSIS II</th>
                <th class="col-bsis3">BSIS III</th>
                <th class="col-bsis4">BSIS IV</th>
                <th class="col-faculty">Faculty/Admin</th>
                <th class="col-carmenanon">Carmenanon</th>
                <th class="col-non-carmenanon">Non-Carmenanon</th>
                <th class="col-complaints">S & S</th>
                <th class="col-medicines">Dispensed Medications/Supplies</th>
                <th class="col-services">Services</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportRows as $row)
                <tr>
                    <td class="col-date">{{ $row['date_label'] }}</td>
                    <td class="col-male">{{ $row['male'] }}</td>
                    <td class="col-female">{{ $row['female'] }}</td>
                    <td class="col-bsis1">{{ $row['bsis1'] }}</td>
                    <td class="col-bsis2">{{ $row['bsis2'] }}</td>
                    <td class="col-bsis3">{{ $row['bsis3'] }}</td>
                    <td class="col-bsis4">{{ $row['bsis4'] }}</td>
                    <td class="col-faculty">{{ $row['faculty_admin'] }}</td>
                    <td class="col-carmenanon">{{ $row['carmenanon'] }}</td>
                    <td class="col-non-carmenanon">{{ $row['non_carmenanon'] }}</td>
                    <td class="col-complaints">{{ $row['complaints'] }}</td>
                    <td class="col-medicines">{{ $row['medicines'] }}</td>
                    <td class="col-services">{{ $row['services'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="col-grand-total"><strong>Grand Total</strong></td>
                <td class="col-male"><strong>{{ $grandTotals['male'] }}</strong></td>
                <td class="col-female"><strong>{{ $grandTotals['female'] }}</strong></td>
                <td class="col-bsis1"><strong>{{ $grandTotals['bsis1'] }}</strong></td>
                <td class="col-bsis2"><strong>{{ $grandTotals['bsis2'] }}</strong></td>
                <td class="col-bsis3"><strong>{{ $grandTotals['bsis3'] }}</strong></td>
                <td class="col-bsis4"><strong>{{ $grandTotals['bsis4'] }}</strong></td>
                <td class="col-faculty"><strong>{{ $grandTotals['faculty_admin'] }}</strong></td>
                <td class="col-carmenanon"><strong>{{ $grandTotals['carmenanon'] }}</strong></td>
                <td class="col-non-carmenanon"><strong>{{ $grandTotals['non_carmenanon'] }}</strong></td>
                <td class="col-complaints">-</td>
                <td class="col-medicines">-</td>
                <td class="col-services">-</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Report generated from CMC Clinic Management System | {{ now()->format('F d, Y H:i A') }}</p>
    </div>
</body>
</html>
