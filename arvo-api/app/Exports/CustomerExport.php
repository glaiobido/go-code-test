<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Customer::all();
    }

    public function headings()
    {
        return [
            'Customer #',
            'Customer',
            'Email',
            'Phone',
            'Trans Date',
            'Trans Time',
            'Type',
            'Notified',
            'Notified Via',
            'Error Message',
        ];
    }
}
