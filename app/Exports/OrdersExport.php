<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        // Convert Collection to array if necessary
        $this->data = $data instanceof Collection ? $data->toArray() : $data;
    }

//    public function collection()
//    {
//        return collect($this->data)->map(function ($order) {
//            return [
//                'product_name' => $order->orderItems->first()->product_name ?? 'لا توجد خدمة',
//                'customer_name' => $order->user->first_name . ' ' . $order->user->family_name,
//                'order_date' => $order->created_at->format('Y-m-d H:i'),
//                'order_status' => $order->orderStatus->name,
//                'payment_status' => $order->payment_status == 'paid' ? 'مدفوع' : 'غير مدفوع',
//            ];
//        });
//    }
    public function collection()
    {
        return collect($this->data)->map(function ($order) {
            return [
                'customer_name' => $order->user->first_name . ' ' . $order->user->family_name,
                'product_name' => $order->products->first()->name ?? 'لا توجد خدمة',
                'product_price' => $order->products->first()->price ?? 'لا توجد سعر',
                'choices' => $order->choices->pluck('name')->join(', ') ?? 'لا توجد اختيارات',
                'choices_service_price' => $order->choices->sum('service_price') . ' ريال',
                'order_date' => $order->created_at->format('Y-m-d H:i'),
//                'order_status' => $order->orderStatus->name,
//                'payment_status' => $order->payment_status == 'paid' ? 'مدفوع' : 'غير مدفوع',
                'discount_applied' => $order->discount_applied ?? '',
                'discount_amount' => $order->discount_applied ? ($order->totalBeforeDiscount - $order->total_price) . ' ريال' : '',
                'total_price' => $order->total_price . ' ريال',
            ];
        });
    }


    public function headings(): array
    {
        return [
            'اسم العميل',
            'اسم الخدمة',
            'سعر الخدمة',
            'الخدمات الاضافة',
            'سعر الخدمات الاضافية',
            'تاريخ الطلب',
//            'حالة الطلب',
//            'حالة الدفع',
            'الكوبون',
            'الخصم',
            'المدفوع',
        ];
    }

}

