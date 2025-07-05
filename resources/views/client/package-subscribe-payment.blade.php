<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.15.0/moyasar.css"/>
    <script src="https://cdn.moyasar.com/mpf/1.15.0/moyasar.js"></script>
</head>

<body>
<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-6 text-center">
            <x-alert type='success'/>
            <x-alert type='danger'/>
            <div class="mysr-form"></div>
        </div>
    </div>
</div>

<script>
    @if ($method === 'applepay')
    Moyasar.init({
        element: '.mysr-form',
        amount: {{$package->price}} * 100,
        currency: 'SAR',
        description: 'Payment for package #{{ $package->id }}',
        publishable_api_key: '{{$publishable_key}}',
        callback_url: '{{ url(route('payment.package_callback', ['package_id' => $package->id])) }}',
        methods: ['applepay'],
        apple_pay: {
            country: 'SA',
            label: 'quick clean',
            validate_merchant_url: 'https://api.moyasar.com/v1/applepay/initiate',
        },
        supported_networks: @json($paymentNetworks),
        metadata: {
            reference: '{{ $reference }}',
        }
    });
    @else
    Moyasar.init({
        element: '.mysr-form',
        amount: {{ $package->price * 100 }},
        currency: 'SAR',
        description: 'Payment for package #{{ $package->id }}',
        publishable_api_key: '{{ $publishable_key }}',
        callback_url: '{{ url(route('payment.package_callback', ['package_id' => $package->id])) }}',
        methods: ['creditcard'], // أو mada حسب الحالة
        supported_networks: @json($paymentNetworks),
        metadata: {
            reference: '{{ $reference }}',
        }
    });
    @endif
</script>

</body>

</html>

