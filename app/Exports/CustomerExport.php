<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerExport implements FromCollection,WithHeadings,ShouldAutoSize,WithStrictNullComparison
{
    /**
    * @return \Illuminate\Support\Collection
    */


    protected $data;

    function __construct($data)
    {
        $this->data=$data;
    }

    
    
    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            
     
            'Name',
            'NRC/Passport',
            'Address/Phone Number'
        ];
    }
}
