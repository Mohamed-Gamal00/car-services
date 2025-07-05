@extends('front.profile.index')
@section('page_title', 'الدفع')

@section('breadcrumb')
    <li> {{__('profile.ADDRESSES')}} </li>
@endsection

@section('front-section')
    {{--    @if(session()->has('success'))--}}
    {{--        <div class="alert alert-success text-center">--}}
    {{--            {{ session()->get('success') }}--}}
    {{--        </div>--}}
    {{--    @endif--}}

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif


    <div class="mysr-form"></div>
@endsection
@push('scripts')
    <script>
        Moyasar.init({
            element: '.mysr-form',
            amount: {{(intval($order->total_price)+ ((float)$order->shipping_price ?? 0))*100}},
            currency: 'SAR',
            description: 'Order number {{$order->number}} by {{  Auth::guard('web')->user()->first_name ?? 'guest' }}',
            publishable_api_key: '{{config('services.moyasar.key')}}',
            callback_url: '{{url(route('payment.callback',[$order->number]))}}',
            methods: ['creditcard']
        })
    </script>
@endpush