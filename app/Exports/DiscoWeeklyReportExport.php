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
use App\Models\Towns;
use App\Models\DisconnectionSchedules;

class DiscoWeeklyReportExport implements ShouldAutoSize, WithColumnFormatting, WithEvents, WithStyles {

    private $data, $from, $to;

    public function __construct($data, $from, $to) {
        $this->data = $data;
        $this->from = $from;
        $this->to = $to;
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

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            'A1:P8' => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
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
                $event->sheet->mergeCells('A1:K1')
                    ->setCellValue('A1', env('APP_COMPANY'));
                $event->sheet->mergeCells('A2:K2')
                    ->setCellValue('A2', env('APP_ADDRESS'));
                $event->sheet->mergeCells('A4:K4')
                    ->setCellValue('A4', 'WEEKLY DISCONNECTION ACCOMPLISHMENT REPORT');
                $event->sheet->mergeCells('A5:K5')
                    ->setCellValue('A5', date('F d, Y', strtotime($this->from)) . ' - ' . date('F d, Y', strtotime($this->to)));

                // TABLE HEADER
                $event->sheet->mergeCells('A7:A8')
                    ->setCellValue('A7', 'DATE');
                $event->sheet->mergeCells('B7:B8')
                    ->setCellValue('B7', 'LOCATION');
                $event->sheet->mergeCells('C7:C8')
                    ->setCellValue('C7', 'BLOCK');
                $event->sheet->mergeCells('D7:D8')
                    ->setCellValue('D7', 'NO. FOR DISCO');
                $event->sheet->mergeCells('E7:F7')
                    ->setCellValue('E7', 'NO. PAID');
                $event->sheet->setCellValue('E8', 'BILLS');
                $event->sheet->setCellValue('F8', 'S.F.');
                $event->sheet->mergeCells('G7:G8')
                    ->setCellValue('G7', '# DISCONNECTED');
                $event->sheet->mergeCells('H7:H8')
                    ->setCellValue('H7', 'ACCTS. W/ REMARKS');
                $event->sheet->mergeCells('I7:I8')
                    ->setCellValue('I7', 'UNFINISHED/NOT FOUND');
                $event->sheet->mergeCells('J7:J8')
                    ->setCellValue('J7', '% ACCOMPLISHED');                    
                $event->sheet->mergeCells('K7:K8')
                    ->setCellValue('K7', 'CREW');   

                $event->sheet->mergeCells('M7:M8')
                    ->setCellValue('M7', 'DATE');               
                $event->sheet->mergeCells('N7:N8')
                    ->setCellValue('N7', 'NUMBER');               
                $event->sheet->mergeCells('O7:P8')
                    ->setCellValue('O7', 'REMARKS');  
                
                $row = 9;
                foreach($this->data as $key => $value) {
                    $event->sheet->setCellValue('A' . $row, strtoupper(date('F d, Y', strtotime($value->Day))));
                    $event->sheet->setCellValue('B' . $row, Towns::parseTownCode(substr($value->Blocks, 0, 2)));
                    $event->sheet->setCellValue('C' . $row, $value->Blocks);
                    $event->sheet->setCellValue('D' . $row, $value->Accounts);
                    $event->sheet->setCellValue('E' . $row, $value->BillsPaid);
                    $event->sheet->setCellValue('F' . $row, $value->BillsPaid);
                    $event->sheet->setCellValue('G' . $row, $value->Disconnected);
                    $event->sheet->setCellValue('H' . $row, $value->WithRemarks);
                    $event->sheet->setCellValue('I' . $row, $value->Unfinished);
                    $event->sheet->setCellValue('J' . $row, DisconnectionSchedules::getPercent($value->Finished, $value->Accounts) . ' %');
                    $event->sheet->setCellValue('K' . $row, strtoupper($value->DisconnectorName));
                    
                    $event->sheet->setCellValue('M' . $row, strtoupper(date('F d, Y', strtotime($value->Day))));
                    $event->sheet->setCellValue('N' . $row, intval($value->WithRemarks) + intval($value->Unfinished));
                    $event->sheet->setCellValue('O' . $row, $value->RemarksPoll);
                    $event->sheet->setCellValue('P' . $row, $value->Unfinished . ' - Unfinished');

                    $row++;
                }

                $event->sheet->getStyle('A7:K' . $row)->applyFromArray($borderStyle);
                $event->sheet->getStyle('M7:P' . $row)->applyFromArray($borderStyle);

            }
        ];
    }
}