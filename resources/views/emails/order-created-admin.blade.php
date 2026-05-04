<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب جديد</title>
    <style>
        body {
            font-family: 'Cairo', 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            direction: rtl;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
        }
        .order-info {
            background-color: #f9f9f9;
            border-right: 4px solid #4CAF50;
            padding: 15px;
            margin: 20px 0;
        }
        .order-info h2 {
            margin-top: 0;
            color: #333;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #45a049;
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #FFC107;
            color: #000;
        }
        .status-paid {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🔔 طلب جديد</h1>
        </div>
        
        <div class="email-body">
            <p>مرحباً،</p>
            <p>تم إنشاء طلب جديد في النظام. إليك التفاصيل:</p>
            
            <div class="order-info">
                <h2>معلومات الطلب</h2>
                
                <div class="info-row">
                    <span class="info-label">رقم الطلب:</span>
                    <span class="info-value">#{{ $order->number }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">اسم العميل:</span>
                    <span class="info-value">{{ $order->user->name ?? 'غير محدد' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">رقم الهاتف:</span>
                    <span class="info-value">{{ $order->user->phone ?? 'غير محدد' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">الخدمة:</span>
                    <span class="info-value">
                        @if($order->service)
                            {{ $order->service->name ?? 'غير محدد' }}
                        @elseif($order->userPackage && $order->userPackage->package)
                            {{ $order->userPackage->package->name ?? 'غير محدد' }} (باقة)
                        @else
                            غير محدد
                        @endif
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">تاريخ الحجز:</span>
                    <span class="info-value">{{ $order->booking_date ?? 'غير محدد' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">وقت الحجز:</span>
                    <span class="info-value">{{ $order->booking_time ?? 'غير محدد' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">السيارة:</span>
                    <span class="info-value">
                        {{ $order->car->name ?? $order->car_model ?? 'غير محدد' }}
                        @if($order->car_number)
                            - {{ $order->car_number }}
                        @endif
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">الموقع:</span>
                    <span class="info-value">{{ $order->address ?? $order->location ?? 'غير محدد' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">طريقة الدفع:</span>
                    <span class="info-value">
                        @if($order->payment_method == 'package')
                            باقة
                        @elseif($order->payment_method == 'creditcard')
                            بطاقة ائتمان
                        @elseif($order->payment_method == 'mada')
                            مدى
                        @elseif($order->payment_method == 'applepay')
                            Apple Pay
                        @else
                            {{ $order->payment_method ?? 'غير محدد' }}
                        @endif
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">حالة الدفع:</span>
                    <span class="info-value">
                        <span class="status-badge {{ $order->payment_status == 'paid' ? 'status-paid' : 'status-pending' }}">
                            {{ $order->payment_status == 'paid' ? 'مدفوع' : 'قيد الانتظار' }}
                        </span>
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">المبلغ الإجمالي:</span>
                    <span class="info-value" style="font-size: 18px; font-weight: bold; color: #4CAF50;">
                        {{ number_format($order->total_price, 2) }} ريال
                    </span>
                </div>
                
                @if($order->discount_applied)
                <div class="info-row">
                    <span class="info-label">كود الخصم:</span>
                    <span class="info-value">{{ $order->discount_applied }}</span>
                </div>
                @endif
                
                @if($order->note)
                <div class="info-row">
                    <span class="info-label">ملاحظات:</span>
                    <span class="info-value">{{ $order->note }}</span>
                </div>
                @endif
            </div>
            
            @if($order->choices && $order->choices->count() > 0)
            <div class="order-info">
                <h2>الخدمات الإضافية</h2>
                @foreach($order->choices as $choice)
                <div class="info-row">
                    <span class="info-label">{{ $choice->name ?? 'خدمة إضافية' }}</span>
                    <span class="info-value">{{ number_format($choice->service_price ?? 0, 2) }} ريال</span>
                </div>
                @endforeach
            </div>
            @endif
            
            <div class="button-container">
                <a href="{{ url('/dashboard/orders/' . $order->id) }}" class="button">
                    عرض تفاصيل الطلب
                </a>
            </div>
            
            <p style="color: #777; font-size: 14px; margin-top: 30px;">
                تم إنشاء هذا الطلب في: {{ $order->created_at->format('Y-m-d H:i:s') }}
            </p>
        </div>
        
        <div class="email-footer">
            <p>هذا البريد الإلكتروني تم إرساله تلقائياً من نظام Quick Clean</p>
            <p>© {{ date('Y') }} Quick Clean. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>
