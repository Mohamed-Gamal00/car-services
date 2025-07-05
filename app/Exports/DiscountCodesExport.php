<?php

namespace App\Exports;

use App\Models\DiscountCode;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DiscountCodesExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        // Convert Collection to array if necessary
        $this->data = $data instanceof Collection ? $data->toArray() : $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->data)->map(function ($coupon) {
            return [
                'code' => $coupon->code,
                'price' => $coupon->price,
                'discount_type' => $coupon->discount_type,
                'status' => $coupon->status,
                'number_of_used' => $coupon->number_of_used,
            ];
        });
    }

    /**
     * Define headings for the exported file
     *
     * @return array
     */

    public function headings(): array
    {
        return [
            'الكود',
            'السعر',
            'نوع الخصم',
            'الحالة',
            'عدد مرات الاستخدام',
        ];
    }
}
