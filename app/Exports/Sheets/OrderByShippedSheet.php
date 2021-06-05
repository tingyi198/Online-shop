<?php

namespace App\Exports\Sheets;

use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrderByShippedSheet implements FromCollection, WithHeadings, WithTitle
{
    public $is_shipped;

    public function __construct($is_shipped)
    {
        $this->is_shipped = $is_shipped;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::where('is_shipped', $this->is_shipped)->get();
    }

    public function headings(): array
    {
        return Schema::getColumnListing('orders');
    }

    public function title(): string
    {
        return $this->is_shipped ? '已運送' : '尚未運送';
    }
}
