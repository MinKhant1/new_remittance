<?php

namespace App\Exports;

use App\Models\Outwards;
use App\Models\OutwardTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Report_outward implements FromCollection, WithHeadings, ShouldAutoSize,WithStyles
{
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
        return [
            1    => ['font' => ['bold' => true]],
        ];

       
    }
    
    public function headings(): array
    {
        return [
            'Tran_No',
            'Branch_ID',
            'Sender Name',
            'Sender NRC/Passport',
            'Sender address/Phone no',
            'Purpose of transaction',
            'Deposit point',
            'Receiver Name',
            'Receiver Country',
            'MMK Amount',
            'Equivent USD',
            'Exchange Rate USD',
           
            'Txd Date Time',
        ];
    }
}
