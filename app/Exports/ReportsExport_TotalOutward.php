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




class ReportsExport_TotalOutward implements FromCollection,WithColumnWidths, WithHeadings, ShouldAutoSize,WithStyles,WithStrictNullComparison
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
        $sheet->mergeCells('D1:E1');
        $sheet->mergeCells('F1:G1');
        return [
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
            
        ];
       
    }

    public function columnWidths(): array
    {
        return [
            'D' => 20,
            'E' => 25,
            'F' => 35,    
            'G' => 35,        
        ];
    }
    
    public function headings(): array
    {
        return [
           
           [ 'No',
            'Date', 
            'Total No.of Trans',          
            'Total Outward Remittance Amount',
            '',
            'Total Outward Remittance Amount From the date of Starting from the Business',
        ],
        ['','','','USD','MMK','USD','MMK']
        ];
    }
}
