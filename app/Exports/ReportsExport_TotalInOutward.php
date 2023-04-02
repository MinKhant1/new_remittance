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




class ReportsExport_TotalInOutward implements FromCollection,WithColumnWidths, WithHeadings, ShouldAutoSize,WithStyles,WithStrictNullComparison
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
        $sheet->mergeCells('C1:E1');
        $sheet->mergeCells('F1:H1');
        $sheet->mergeCells('I1:K1');
     

        return [
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],

            
        ];
       
    }

    public function columnWidths(): array
    {
        return [
            'D' => 20,
            'E' => 20,
            'F' => 20,    
            'G' => 20,        
            'H' => 20,    
            'I' => 20,
            'J' => 20,    
            'K' => 20,
        ];
    }
    
    public function headings(): array
    {
        return [
           
           [ 'No',
            'Date', 
            'Total inwward Amount', 
            '',  
            '',       
            'Total Outward Amount',
            '',
            '',
            'Net Amount',
        ],
        ['','','Total No.of Trans','USD','MMK','Total No.of Trans','USD','MMK','Total No.of Trans','USD','MMK']
        ];
    }
}
