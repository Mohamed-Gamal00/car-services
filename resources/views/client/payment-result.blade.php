<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('general.payment_result') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        
        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }
        
        .icon.success {
            background: #d4edda;
            color: #28a745;
        }
        
        .icon.failed {
            background: #f8d7da;
            color: #dc3545;
        }
        
        .icon.error {
            background: #fff3cd;
            color: #ffc107;
        }
        
        h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }
        
        p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .order-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: right;
        }
        
        .order-info p {
            margin: 10px 0;
            color: #333;
        }
        
        .order-info strong {
            color: #667eea;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s;
            margin-top: 20px;
        }
        
        .btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn.secondary {
            background: #6c757d;
        }
        
        .btn.secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($status === 'success')
            <div class="icon success">✓</div>
            <h1>{{ __('general.payment_successful') }}</h1>
            <p>{{ $message }}</p>
            
            @if(isset($order))
            <div class="order-info">
                <p><strong>{{ __('general.order_number') }}:</strong> {{ $order->number }}</p>
                <p><strong>{{ __('general.total_amount') }}:</strong> {{ $order->total_price }} {{ __('general.sar') }}</p>
                <p><strong>{{ __('general.payment_status') }}:</strong> {{ __('general.paid') }}</p>
            </div>
            @endif
            
            <a href="/" class="btn">{{ __('general.back_to_home') }}</a>
            
        @elseif($status === 'failed')
            <div class="icon failed">✗</div>
            <h1>{{ __('general.payment_failed') }}</h1>
            <p>{{ $message }}</p>
            
            @if(isset($order))
            <div class="order-info">
                <p><strong>{{ __('general.order_number') }}:</strong> {{ $order->number }}</p>
                <p>{{ __('general.payment_failed_message') }}</p>
            </div>
            
            <a href="{{ route('user.payment', ['order_number' => $order->number, 'method' => 'creditcard']) }}" class="btn">{{ __('general.try_again') }}</a>
            @endif
            
            <a href="/" class="btn secondary">{{ __('general.back_to_home') }}</a>
            
        @else
            <div class="icon error">!</div>
            <h1>{{ __('general.payment_error') }}</h1>
            <p>{{ $message }}</p>
            
            <a href="/" class="btn">{{ __('general.back_to_home') }}</a>
        @endif
    </div>
</body>
</html>
