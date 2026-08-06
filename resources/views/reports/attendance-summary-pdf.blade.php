<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Attendance Summary Report</title>
<style>
    body { font-family: "DejaVu Sans", sans-serif; font-size: 11px; color: #0b0b0b; }
    h1 { font-size: 17px; margin: 0 0 4px; }
    .meta { color: #52514e; font-size: 10px; margin-bottom: 14px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #c3c2b7; padding: 5px 7px; text-align: left; }
    th { background-color: #f4f3f0; font-weight: bold; }
    td.num, th.num { text-align: right; }
    tr.below-threshold { background-color: #f8d7da; }
    .legend { margin-top: 10px; font-size: 9px; color: #52514e; }
    .legend .swatch {
        display: inline-block; width: 10px; height: 10px; background: #f8d7da;
        border: 1px solid #d03b3b; margin-right: 4px; vertical-align: middle;
    }
</style>
</head>
<body>
    <h1>Attendance Summary Report</h1>
    <div class="meta">
        Period: {{ $periodLabel }} &nbsp;|&nbsp;
        Class: {{ $classLabel }} &nbsp;|&nbsp;
        Teacher: {{ $teacherLabel }} &nbsp;|&nbsp;
        Generated: {{ now()->format('d M Y, h:i A') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Roll No</th>
                <th>Name</th>
                <th>Class</th>
                <th class="num">Total</th>
                <th class="num">Present</th>
                <th class="num">Late</th>
                <th class="num">Leave</th>
                <th class="num">Absent</th>
                <th class="num">Attendance %</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr class="{{ $row['below_threshold'] ? 'below-threshold' : '' }}">
                    <td>{{ $row['roll_number'] }}</td>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['class'] }}</td>
                    <td class="num">{{ $row['total'] }}</td>
                    <td class="num">{{ $row['present'] }}</td>
                    <td class="num">{{ $row['late'] }}</td>
                    <td class="num">{{ $row['leave'] }}</td>
                    <td class="num">{{ $row['absent'] }}</td>
                    <td class="num">{{ $row['percentage'] === null ? 'N/A' : $row['percentage'].'%' }}</td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center;">No students found for this filter.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="legend"><span class="swatch"></span> Below {{ \App\Support\AttendanceSummaryReport::THRESHOLD }}% attendance threshold</div>
</body>
</html>
