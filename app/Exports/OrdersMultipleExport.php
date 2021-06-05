<?php

namespace App\Exports;

use App\Exports\Sheets\OrderByShippedSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OrdersMultipleExport implements WithMultipleSheets
{

    public function sheets(): array
    {
        $sheets = [];
        $shippedArr = [true, false]; // true: 已運送, false: 尚未運送

        foreach($shippedArr as $is_shipped) {
            $sheets[] = new OrderByShippedSheet($is_shipped);
        }

        return $sheets;
    }
}
