<?php

namespace App\Exports;

use App\Models\Inwards;
use App\Models\InwardTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithColumnWidths;




class ReportsExport_TotalInward implements FromCollection, WithHeadings, WithColumnWidths, ShouldAutoSize,WithStyles,WithStrictNullComparison
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $query;
   

    function __construct($query)
    {
        $this->query=$query;
       
        
    }
    public function collection()
    {

        return $this->query;
    }

    public function styles(Worksheet $sheet)
    { 
        $sheet->mergeCells('N1:O1');
        $sheet->mergeCells('P1:Q1');
        return [
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
            
        ];
       
    }

    public function columnWidths(): array
    {
        return [
            'N' => 20,
            'O' => 25,
            'P' => 30,    
            'Q' => 30,        
        ];
    }
    
    public function headings(): array
    {
        return [
           
           [ 'No',
            'Date',
            'USD',
            'EUR',
            'JPY',
            'KRW',
            'MYR',
            'SGD',
            'THB',
            'AED',
            'QAR',
            'Other Country',
            'Total No.of Trans',
            'Total Inward Remittance Amount',
            '',
            'Total Inward Remittance Amount From the date of Starting from the Business',
        ],
        ['','','','','','','','','','','','','','USD','MMK','USD','MMK']
        ];
    }
}
