<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 20px;
            direction: rtl;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }

        .invoice-details {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-details td, .invoice-details th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .invoice-details th {
            background-color: #f4f4f4;
        }

        .details-row {
            width: 50%;
        }
    </style>
</head>
<body>


<table class="w-full">
    <tr>
        <td class="w-half">
            <img style="width: 150px" src="{{ asset('assets/images/quick-clean.jpg') }}" alt="Quick Clean Logo">

        </td>
        <td class="w-half">
            <h4>الفاتورة: {{ $data['booking_date'] }}</h4>
            <h4>تاريخ الحجز: {{ $data['booking_date'] }}</h4>
            <h4>وقت الحجز: {{ $data['booking_time'] }}</h4>
        </td>
    </tr>
</table>


<table class="invoice-details">
    <tr>
        <td class="details-row">
            <h4>رقم الطلب : {{ $data['order_number'] }}</h4>
        </td>
        <td class="details-row">
            <h4>اسم العميل: {{ $data['user_name'] }}</h4>
            <h4>رقم الهاتف: {{ $data['user_phone'] }}</h4>
            <h4>اسم الخدمة: {{ $data['service_name'] }}</h4>
            <h4>وقت الخدمة: {{ $data['service_duration'] }}</h4>
            <h4>سعر الخدمة: {{ $data['service_price'] }}</h4>
        </td>
    </tr>
</table>

<h3>تفاصيل الطلب:</h3>
<table class="invoice-details">
    <tr>
        <th>اسم السيارة</th>
        <th>النوع</th>
        <th>رقم السيارة</th>
        <th>نوع الدفع</th>
        <th>حالة الدفع</th>
        <th>كود الخصم</th>
        <th>الاجمالي</th>
        <th>السعر قبل الخصم</th>
    </tr>
    <tr>
        <td>{{ $data['car_name'] }}</td>
        <td>{{ $data['car_model'] }}</td>
        <td>{{ $data['car_number'] }}</td>
        <td>{{ $data['payment_method'] }}</td>
        <td>{{ $data['payment_status'] }}</td>
        <td>{{ $data['discount_applied'] }}</td>
        <td>{{ $data['total_price'] }}</td>
        <td>{{ $data['totalBeforeDiscount'] }}</td>
    </tr>
</table>

<h3>خدمات اضافية:</h3>
<table class="invoice-details">
    <tr>
        <th>اسم الخدمة</th>
        <th>السعر</th>
    </tr>
    @forelse($data['service_choices'] as $choice)
        <tr>
            <td>{{ $choice->getCurrentNameLangAttribute() }}</td>
            <td>{{ $choice->service_price }}</td>
        </tr>
    @empty
        <p>لا يوجد</p>
    @endforelse


</table>
<div>
    @if($data['discount_applied'])
        <h4>الخصم
            : {{ $data['discount_applied'] ?($data['totalBeforeDiscount'] - $data['total_price']  ).' '.'ريال':  ''}}
        </h4>
    @endif
    <h4>الاجمالي النهائي: {{$data['total_price']}} ريال</h4>
    <?php

    use App\Models\Setting;

    $setting = Setting::first();
    ?>
    @if($setting->tax_number)
        <h4>الرقم الضريبي: {{ $setting->tax_number }}</h4>
    @endif

</div>
</body>
</html>
