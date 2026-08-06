<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceSummaryExport;
use App\Models\ClassRoom;
use App\Models\User;
use App\Support\AttendanceChartData;
use App\Support\AttendanceSummaryReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Powers the "Attendance Summary" report on the shared attendance/reports
 * page: a per-student attendance % table (highlighting anyone below
 * AttendanceSummaryReport::THRESHOLD), plus PDF/Excel downloads of the same
 * data. Reachable by admins and teachers alike (this sits behind the same
 * ['auth', 'teacher'] middleware as the rest of routes/web.php's "attendance."
 * group, which already lets admins through) — the scoping below is what
 * keeps a teacher from ever seeing another class's numbers by hand-editing
 * the query string.
 */
class ReportController extends Controller
{
    public function summaryData(Request $request)
    {
        $rows = $this->buildRows($request);

        return response()->json(['rows' => $rows->values()]);
    }

    public function summaryPdf(Request $request)
    {
        $rows = $this->buildRows($request);

        $pdf = Pdf::loadView('reports.attendance-summary-pdf', [
            'rows' => $rows,
            'periodLabel' => $this->periodLabel($request),
            'classLabel' => $this->classLabel($request),
            'teacherLabel' => $this->teacherLabel($request),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('attendance-summary-'.$this->monthInput($request).'.pdf');
    }

    public function summaryExcel(Request $request)
    {
        $rows = $this->buildRows($request);

        return Excel::download(
            new AttendanceSummaryExport($rows, 'Attendance Summary'),
            'attendance-summary-'.$this->monthInput($request).'.xlsx'
        );
    }

    protected function buildRows(Request $request)
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
            'class_id' => 'nullable|exists:classes,id',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        [$start, $end] = AttendanceChartData::resolveMonthRange($request->input('month'));
        [$classIds, $teacherId] = $this->resolveScope($request);

        return AttendanceSummaryReport::build($start, $end, $classIds, $teacherId);
    }

    /**
     * Admins may filter by any class/teacher; teachers are locked to the
     * class(es) they're the class teacher of and to their own sessions,
     * regardless of what's in the query string.
     *
     * @return array{0: array<int, int>|null, 1: int|null}
     */
    protected function resolveScope(Request $request): array
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $classIds = $request->filled('class_id') ? [(int) $request->input('class_id')] : null;
            $teacherId = $request->filled('teacher_id') ? (int) $request->input('teacher_id') : null;

            return [$classIds, $teacherId];
        }

        $classIds = ClassRoom::where('class_teacher_id', $user->id)->pluck('id')->all();

        return [$classIds, $user->id];
    }

    protected function monthInput(Request $request): string
    {
        return $request->input('month', now()->format('Y-m'));
    }

    protected function periodLabel(Request $request): string
    {
        [$start, $end] = AttendanceChartData::resolveMonthRange($request->input('month'));

        return $start->format('F Y').' ('.$start->format('M d').' - '.$end->format('M d').')';
    }

    protected function classLabel(Request $request): string
    {
        if (! $request->user()->isAdmin()) {
            $names = ClassRoom::where('class_teacher_id', $request->user()->id)
                ->get()
                ->map(fn (ClassRoom $c) => trim($c->name.' '.$c->section))
                ->implode(', ');

            return $names !== '' ? $names : 'My Class';
        }

        if ($request->filled('class_id')) {
            $class = ClassRoom::find($request->input('class_id'));

            return $class ? trim($class->name.' '.$class->section) : 'All Classes';
        }

        return 'All Classes';
    }

    protected function teacherLabel(Request $request): string
    {
        if (! $request->user()->isAdmin()) {
            return $request->user()->name;
        }

        if ($request->filled('teacher_id')) {
            $teacher = User::find($request->input('teacher_id'));

            return $teacher?->name ?? 'All Teachers';
        }

        return 'All Teachers';
    }
}
