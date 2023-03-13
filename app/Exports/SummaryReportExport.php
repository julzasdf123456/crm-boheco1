<?php

namespace App\Exports;

use App\Models\Tickets;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class SummaryReportExport implements FromArray, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithCustomStartCell {

    private $data, $month, $summary, $billSummary;

    public function __construct(array $data, $month, $summary, $billSummary) {
        $this->data = $data;
        $this->month = $month;
        $this->summary = $summary;
        $this->billSummary = $billSummary;
    }

    public function headings(): array
    {
        return [
            "Town",
            "Total Applicants",
            "Verified Applicant based on this Month's Applicantions",
            "For Inspections Based on this Month's Applicantions",
            "Executed Based on this Month's Applications",
            "Total Inspections For this Month",
            "Total Energization For this Month",
        ];
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function array(): array {
        return $this->data;
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_TEXT,
            'L' => NumberFormat::FORMAT_TEXT,
            'M' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $borderStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ]
                ];
                // HEADER
                $event->sheet->mergeCells(sprintf('A1:L1'));
                $event->sheet->getStyle('A1:L1')
                        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1:L1')
                    ->getFont()->setBold(true);
                $event->sheet->setCellValue('A1', env('APP_COMPANY'));
                
                $event->sheet->mergeCells(sprintf('A2:L2'));
                $event->sheet->getStyle('A2:L2')
                        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A2:L2')
                    ->getFont()->setBold(true);
                $event->sheet->setCellValue('A2', env('APP_ADDRESS'));

                $event->sheet->mergeCells(sprintf('A4:L4'));
                $event->sheet->getStyle('A4:L4')
                        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A4:L4')
                    ->getFont()->setBold(true);
                $event->sheet->setCellValue('A4', 'HOUSEWIRING SUMMARY REPORT FOR ' . strtoupper(date('F Y', strtotime($this->month))));

                // TABLE HEADERS
                $event->sheet->mergeCells(sprintf('H6:J6'));
                $event->sheet->getStyle('H6:L6')
                        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A6:L6')
                    ->getFont()->setBold(true);
                $event->sheet->setCellValue('H6', 'Total Released Turn On Orders');

                $event->sheet->getStyle('H7:J7')
                        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->setCellValue('H7', 'MO');
                $event->sheet->setCellValue('I7', 'SO');
                $event->sheet->setCellValue('J7', 'TTL');

                $event->sheet->setCellValue('H8', $this->summary != null ? $this->summary->EOIssuedMain : 0);
                $event->sheet->setCellValue('I8', $this->summary != null ? $this->summary->EOIssuedSub : 0);
                $event->sheet->setCellValue('J8', $this->summary != null ? ($this->summary->EOIssuedSub + $this->summary->EOIssuedMain) : 0);

                $event->sheet->setCellValue('K6', 'Complete Billed Consumers for ' . date('F Y', strtotime($this->billSummary->PrevMonth)));
                $event->sheet->setCellValue('L6', 'Billed Consumers for ' . date('F Y', strtotime($this->billSummary->CurrentMonth)) . ' as of ' . date(' M d, Y'));
                $event->sheet->setCellValue('K7', number_format($this->billSummary->PrevMonthBillsTotal));
                $event->sheet->setCellValue('L7', number_format($this->billSummary->BillsTotalAsOf));

                $event->sheet->getStyle('A33:L33')
                    ->getFont()->setBold(true);
            }
        ];
    }
}