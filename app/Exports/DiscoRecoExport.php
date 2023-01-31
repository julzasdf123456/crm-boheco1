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

class DiscoRecoExport implements ShouldAutoSize, WithColumnFormatting, WithEvents {

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
                
                $row = 1;
                $len = count($this->data);
                $dataRow = 16;
                $footer = 23;
                $iterations = 0;
                foreach($this->data as $key => $value) {
                    if (($key % $dataRow) == 0) {
                        if ($iterations > 0) {
                            $event->sheet->setCellValue('B' . $row, 'PREPARED BY');
                            $event->sheet->setCellValue('D' . $row, 'CHECKED BY');
                            $event->sheet->setCellValue('F' . $row, 'NOTED BY');
                            $event->sheet->setCellValue('J' . $row, 'RECEIVED BY');
                            $row += 2;
                            $event->sheet->getStyle('B' . $row . ':C' . $row)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->mergeCells('B' . $row . ':C' . $row)
                                ->setCellValue('B' . $row, env('ESD_CLERK'));

                            $event->sheet->getStyle('D' . $row . ':E' . $row)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->mergeCells('D' . $row . ':E' . $row)
                                ->setCellValue('D' . $row, env('ESD_CHIEF_OPERATIONS'));

                            $event->sheet->getStyle('F' . $row . ':H' . $row)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->mergeCells('F' . $row . ':H' . $row)
                                ->setCellValue('F' . $row, env('ESD_MANAGER'));

                            $event->sheet->getStyle('J' . $row . ':K' . $row)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->mergeCells('J' . $row . ':K' . $row)
                                ->setCellValue('J' . $row, '_______________________');
                            $row += 1;

                            $event->sheet->getStyle('B' . $row . ':C' . $row)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->mergeCells('B' . $row . ':C' . $row)
                                ->setCellValue('B' . $row, 'ESD Clerk');

                            $event->sheet->getStyle('D' . $row . ':E' . $row)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->mergeCells('D' . $row . ':E' . $row)
                                ->setCellValue('D' . $row, 'Chief, COMPD');

                            $event->sheet->getStyle('F' . $row . ':H' . $row)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->mergeCells('F' . $row . ':H' . $row)
                                ->setCellValue('F' . $row, 'Manager ESD');

                            $event->sheet->getStyle('J' . $row . ':K' . $row)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->mergeCells('J' . $row . ':K' . $row)
                                ->setCellValue('J' . $row, 'Signature & Date');
                            $row += 2;
                        }

                        $event->sheet->mergeCells(sprintf('A' . $row . ':M' . $row));
                        $event->sheet->getStyle('A' . $row . ':M' . $row)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $event->sheet->getStyle('A' . $row . ':M' . $row)
                            ->getFont()->setBold(true);
                        $event->sheet->setCellValue('A' . $row, env('APP_COMPANY'));
                        $row += 1;
                        
                        $event->sheet->mergeCells(sprintf('A' . $row . ':M' . $row));
                        $event->sheet->getStyle('A' . $row . ':M' . $row)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $event->sheet->getStyle('A' . $row . ':M' . $row)
                            ->getFont()->setBold(true);
                        $event->sheet->setCellValue('A' . $row, env('APP_ADDRESS'));
                        $row += 2;
                        $event->sheet->mergeCells(sprintf('A' . $row . ':M' . $row));       
                        $event->sheet->getStyle('A' . $row . ':M' . $row)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);     
                        $event->sheet->getStyle('A' . $row . ':M' . $row)
                            ->getFont()->setBold(true);          
                        $event->sheet->setCellValue('A' . $row, 'DISCONNECTION/RECONNECTION REPORT FROM ' . date('M d, Y', strtotime($this->from)) . ' TO ' . date('M d, Y', strtotime($this->to)));

                        $row += 2;
                        $event->sheet->getStyle('A' . $row)
                            ->getFont()->setBold(true); 
                        $event->sheet->setCellValue('A' . $row, 'No');
                        $event->sheet->getStyle('B' . $row)
                            ->getFont()->setBold(true);
                        $event->sheet->setCellValue('B' . $row, 'Date Executed');
                        $event->sheet->getStyle('C' . $row)
                            ->getFont()->setBold(true);
                        $event->sheet->setCellValue('C' . $row, 'Ticket No');
                        $event->sheet->getStyle('D' . $row)
                            ->getFont()->setBold(true);
                        $event->sheet->setCellValue('D' . $row, 'Account No');
                        $event->sheet->getStyle('E' . $row)
                            ->getFont()->setBold(true);
                        $event->sheet->setCellValue('E' . $row, 'Consumer Name');
                        $event->sheet->getStyle('F' . $row)
                            ->getFont()->setBold(true);
                        $event->sheet->setCellValue('F' . $row, 'Address');
                        $event->sheet->getStyle('G' . $row)
                            ->getFont()->setBold(true);
                        $event->sheet->setCellValue('G' . $row, 'Ticket');
                        $event->sheet->getStyle('H' . $row)
                            ->getFont()->setBold(true);
                        $event->sheet->setCellValue('H' . $row, 'Old Meter Brand');
                        $event->sheet->getStyle('I' . $row)
                            ->getFont()->setBold(true);
                        $event->sheet->setCellValue('I' . $row, 'Old Meter Number');
                        $event->sheet->getStyle('J' . $row)
                            ->getFont()->setBold(true);
                        $event->sheet->setCellValue('J' . $row, 'Old Meter Reading');
                        $event->sheet->getStyle('K' . $row)
                            ->getFont()->setBold(true);
                        $event->sheet->setCellValue('K' . $row, 'Crew Assigned');
                        $event->sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray($borderStyle);

                        $row += 1;
                        $event->sheet->setCellValue('A' . $row, ($key+1));
                        $event->sheet->setCellValue('B' . $row, date('M d, Y h:m:s A', strtotime($value->DateTimeLinemanExecuted)));
                        $event->sheet->setCellValue('C' . $row, $value->id);
                        $event->sheet->setCellValue('D' . $row, $value->AccountNumber);
                        $event->sheet->setCellValue('E' . $row, $value->ConsumerName);
                        $event->sheet->setCellValue('F' . $row, Tickets::getAddress($value));
                        $event->sheet->setCellValue('G' . $row, $value->ParentTicket . '-' . $value->Name);
                        $event->sheet->setCellValue('H' . $row, $value->CurrentMeterBrand);
                        $event->sheet->setCellValue('I' . $row, $value->CurrentMeterNo);
                        $event->sheet->setCellValue('J' . $row, $value->CurrentMeterReading);
                        $event->sheet->setCellValue('K' . $row, $value->StationName);
                        $event->sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray($borderStyle);
                        $row += 1;

                        $iterations++;
                    } else {                        
                        $event->sheet->setCellValue('A' . $row, ($key+1));
                        $event->sheet->setCellValue('B' . $row, date('M d, Y h:m:s A', strtotime($value->DateTimeLinemanExecuted)));
                        $event->sheet->setCellValue('C' . $row, $value->id);
                        $event->sheet->setCellValue('D' . $row, $value->AccountNumber);
                        $event->sheet->setCellValue('E' . $row, $value->ConsumerName);
                        $event->sheet->setCellValue('F' . $row, Tickets::getAddress($value));
                        $event->sheet->setCellValue('G' . $row, $value->ParentTicket . '-' . $value->Name);
                        $event->sheet->setCellValue('H' . $row, $value->CurrentMeterBrand);
                        $event->sheet->setCellValue('I' . $row, $value->CurrentMeterNo);
                        $event->sheet->setCellValue('J' . $row, $value->CurrentMeterReading);
                        $event->sheet->setCellValue('K' . $row, $value->StationName);
                        $event->sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray($borderStyle);
                        $row += 1;
                    }
                }

                $event->sheet->setCellValue('B' . $row, 'PREPARED BY');
                $event->sheet->setCellValue('D' . $row, 'CHECKED BY');
                $event->sheet->setCellValue('F' . $row, 'NOTED BY');
                $event->sheet->setCellValue('J' . $row, 'RECEIVED BY');
                $row += 2;
                $event->sheet->getStyle('B' . $row . ':C' . $row)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('B' . $row . ':C' . $row)
                    ->setCellValue('B' . $row, env('ESD_CLERK'));

                $event->sheet->getStyle('D' . $row . ':E' . $row)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('D' . $row . ':E' . $row)
                    ->setCellValue('D' . $row, env('ESD_CHIEF_OPERATIONS'));

                $event->sheet->getStyle('F' . $row . ':H' . $row)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('F' . $row . ':H' . $row)
                    ->setCellValue('F' . $row, env('ESD_MANAGER'));

                $event->sheet->getStyle('J' . $row . ':K' . $row)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('J' . $row . ':K' . $row)
                    ->setCellValue('J' . $row, '_______________________');
                $row += 1;

                $event->sheet->getStyle('B' . $row . ':C' . $row)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('B' . $row . ':C' . $row)
                    ->setCellValue('B' . $row, 'ESD Clerk');

                $event->sheet->getStyle('D' . $row . ':E' . $row)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('D' . $row . ':E' . $row)
                    ->setCellValue('D' . $row, 'Chief, COMPD');

                $event->sheet->getStyle('F' . $row . ':H' . $row)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('F' . $row . ':H' . $row)
                    ->setCellValue('F' . $row, 'Manager ESD');

                $event->sheet->getStyle('J' . $row . ':K' . $row)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('J' . $row . ':K' . $row)
                    ->setCellValue('J' . $row, 'Signature & Date');
                $row += 2;  
            }
        ];
    }
}