<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Same "table-danger" red used on screen (Bootstrap's #f8d7da) fills rows
 * below the 80% threshold here too, so the exported file reads the same as
 * the dashboard it came from.
 *
 * Implements WithStrictNullComparison because PhpSpreadsheet's default loose
 * null check (`0 == null` is true in PHP) silently blanks out genuine 0
 * counts — e.g. "0 absences" — instead of writing them as 0. Without this,
 * a perfect attendance row reads as missing data instead of a zero.
 */
class AttendanceSummaryExport implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping, WithStrictNullComparison, WithTitle
{
    private const HIGHLIGHT_COLOR = 'F8D7DA';

    private const HEADER_COLOR = 'F4F3F0';

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     */
    public function __construct(protected Collection $rows, protected string $sheetTitle = 'Attendance Summary')
    {
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return ['Roll No', 'Name', 'Class', 'Total Sessions', 'Present', 'Late', 'Leave', 'Absent', 'Attendance %'];
    }

    public function map($row): array
    {
        return [
            $row['roll_number'],
            $row['name'],
            $row['class'],
            $row['total'],
            $row['present'],
            $row['late'],
            $row['leave'],
            $row['absent'],
            $row['percentage'] === null ? 'N/A' : $row['percentage'].'%',
        ];
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = 'I';

                $sheet->getStyle('A1:'.$lastColumn.'1')->getFont()->setBold(true);
                $sheet->getStyle('A1:'.$lastColumn.'1')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB(self::HEADER_COLOR);

                foreach ($this->rows->values() as $index => $row) {
                    if (! ($row['below_threshold'] ?? false)) {
                        continue;
                    }

                    $excelRow = $index + 2; // +1 for 1-indexing, +1 for the header row
                    $sheet->getStyle('A'.$excelRow.':'.$lastColumn.$excelRow)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB(self::HIGHLIGHT_COLOR);
                }
            },
        ];
    }
}
