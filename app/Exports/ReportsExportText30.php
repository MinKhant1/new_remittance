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



class ReportsExportText30 implements FromCollection, WithHeadings, ShouldAutoSize,WithStyles
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
        return [
            1    => ['font' => ['bold' => true]],
        ];

       
    }
    
    public function headings(): array
    {
        return [
            'Tran_No',
            'Branch_ID',
            'Receiver Name',
            'Receiver NRC/Passport',
            'Receiver address/Phone no',
            'Purpose of transaction',
            'Withdraw point',
            'Sender Name',
            'Sender Country',
            'Currency',
            'Amount',
            'Equivent USD',
            'MMK Amount',
            'MMK Allowance',
            'Total MMK Amount',
            'Exchange Rate',
            'Exchange Rate USD',
            'Txd Date Time',
        ];
    }
}
