<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements WithHeadingRow, ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Customer([
            'firstname' => $row['cust_fname'],
            'customer_number' => $row['cust_num'],
            'email' => $row['cust_email'],
            'trans_type' => $row['trans_type'],
            'trans_date' => $row['trans_date'],
            'trans_time' => $row['trans_time'],
            'phone' => $row['cust_phone']
        ]);
    }
}
