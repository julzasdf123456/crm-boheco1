<?php

namespace App\Exports;

use App\Models\ServiceConnections;
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
use Illuminate\Support\Facades\DB;

class TicketsQuarterlyExport implements FromArray, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithStyles, WithEvents, WithCustomStartCell {

    private $tickets, $quarter;

    public function __construct(array $tickets, $quarter) {
        $this->tickets = $tickets;
        $this->quarter = $quarter;
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function array(): array {
        return $this->tickets;
    }

    public function headings(): array
    {
        return [
            'Count',
            'Account Number',
            'Consumer Name',
            'Address',
            'Nature of Complaint',
            'Date Received',
            'Action Desired',
            'Action Taken',
            'Date Acted',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            3 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            4 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            
            5 => [
               'font' => ['bold' => true],
               'alignment' => ['horizontal' => 'center'],
           ],
            7 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $event->sheet->mergeCells(sprintf('A2:I2'));
                $event->sheet->mergeCells(sprintf('A3:I3'));
                $event->sheet->mergeCells(sprintf('A4:I4'));
                $event->sheet->mergeCells(sprintf('A5:I5'));
                
                $event->sheet->setCellValue('A2', env('APP_COMPANY'));
                $event->sheet->setCellValue('A3', env('APP_ADDRESS'));
                $event->sheet->setCellValue('A4', 'SUMMARY OF COMPLAINTS RECEIVED');
                $event->sheet->setCellValue('A5', $this->quarter);
                
                // SET TOTAL
                // $totalRow = count($this->billOfMaterials) + 8;
                // $event->sheet->mergeCells(sprintf('A' . $totalRow . ':D' . $totalRow));
                // $event->sheet->setCellValue('A' . $totalRow, 'Total');
                // $event->sheet->setCellValue('E' . $totalRow, '=SUM(E8:E' . ($totalRow-1) . ')');
                // $event->sheet->getStyle('E' . ($totalRow-1) . ')')->getNumberFormat()
                // ->setFormatCode('#,##0.00');
            }
        ];
    }
}