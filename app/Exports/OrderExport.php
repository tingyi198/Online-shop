<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class OrderExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    public $dataCount;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $orders = Order::with(['user', 'cart.cartItems.product'])->get();
        $orders = $orders->map(function ($order) {
            return [
                $order->id,
                $order->user->name,
                $order->is_shipped,
                $order->cart->cartItems->sum(function ($cartItem) {
                    return $cartItem->product->price * $cartItem->quantity;
                }),
                Date::dateTimeToExcel($order->created_at)
            ];
        });

        $this->dataCount = $orders->count();
        return $orders;
    }

    public function headings(): array
    {
        return ['編號', '購買者', '是否運送', '總價', '建立時間'];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(23);
                $sheet->freezePane('F2');  // 凍結頂端列

                // set each row height
                for ($i = 2; $i <= $this->dataCount+1; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(25);
                }
                $sheet->getRowDimension(1)->setRowHeight(25);

                // set each row style
                $dataStyleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'font' => [
                        'argb' => '#555555',
                        'name' => 'Calibri'
                    ]
                ];
                $sheet->getStyle('A2:E' . $this->dataCount + 1)->applyFromArray($dataStyleArray);

                // set head style
                $headStyleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => '6E71D2'
                        ]
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => '14',
                        'name' => 'Calibri',
                        'color' => [
                            'argb' => 'FFFFFF'
                        ]
                    ]
                ];
                $sheet->getStyle('A1:E1')->applyFromArray($headStyleArray);

                // set date format
                $sheet->getStyle('E2:E'.$this->dataCount + 1)
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD2);

            }
        ];
    }


}
