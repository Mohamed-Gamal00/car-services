@extends('front.profile.index')

@section('page_title', 'الطلبات')
@push('styles')
    <link rel="stylesheet" href="{{ asset('front/css/old-style.css') }}">
@endpush

@section('user_section')
    <x-front.alert type="danger"/>
    <x-front.alert type="success"/>
    <div class="account-content">
        <div id="Orders" class="tab-pane fade in active">
            <div class="col-md-12 col-sm-8 col-xs-12">
                <div class="ordersContent">
                    <h3>{{ __('profile.ORDERS') }}</h3>
                    @forelse ($orders as $order)
                        {{-- حالة الطلب --}}
                        <div class="statues">
                            <ul>
                                @forelse($orderStatus as $status)
                                    <li style="font-size: 12px;"
                                        class="{{ $order->order_status_id == $status->id ? 'active' : '' }}"><i
                                                class="fa fa-check-circle"></i>{{ $status->CurrentNameLang }}</li>
                                @empty
                                @endforelse

                                <a href="{{ route('guest.orders', $order->number) }}">
                                    @csrf
                                    @method('delete')
                                    <button style="border: none; background: none ;float: inline-end;">
                                        <li style="color: forestgreen;font-size: 12px;" class=""><i
                                                    class="fa fa-eye"></i> مشاهدة
                                            {{ __('profile.ORDER') }}
                                        </li>
                                    </button>
                                    <input hidden name="order_id" value="{{ $order->id }}">
                                </a>
                            </ul>
                        </div>
                        {{-- الطلبات --}}
                        <div class="one-order">
                            <div class="pic">
                                <img height="" src="{{ asset('front/images/order_image.png') }}" alt="">
                            </div>
                            <div class="data">
                                <div class="data">
                                    <h5> {{ __('profile.ORDER_NUMBER') }}: #{{ $order->number }} </h5>
                                    @if ($order->shipping_price)
                                        <h2>
                                            الاجمالي :
                                            <span>{{ resolve('App\currency\Currency')->getCurrency($order->total_price + intval($order->shipping_price)) }}</span>
                                        </h2>
                                    @else
                                        <h2>
                                            الاجمالي :
                                            <span>{{ resolve('App\currency\Currency')->getCurrency($order->total_price) }}</span>
                                        </h2>
                                    @endif
                                    {{-- <h2>عدد الخدمات {{$order->orderItems->count()}}</h2> --}}
                                </div>

                                @if ($order->payment_status == 'paid')
                                    <p style="color: green; font-size: 12px;" class="">
                                        <i class="fa fa-check"></i> {{ __('profile.PAID') }}
                                    </p>
                                @endif
                                @if ($order->order_status_id == $status->arrangement)
                                    <form action="{{ route('guest.return_products.store') }}" method="post">
                                        @csrf

                                        <button type="submit" style="border: none; background-color: white;">
                                            <h2 style="color:red;font-size: 12px;">ارجاع</h2>
                                        </button>
                                        <input hidden value="{{ $order->id }}" name="return_order_id">
                                    </form>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="one-order">
                            <div class="pic">
                                <img height="100px" src="{{ $order->orderItems->first()->product->image_url }}"
                                    alt="">
                            </div>
                            <div class="data">
                                <h5>{{ $order->orderItems->first()->product->parent->name }}</h5>
                                <h2>{{ $order->orderItems->first()->product->name }}</h2>
                                <p> {{ $order->orderItems->first()->product->price }}</p>
                            </div>
                            @if ($order->order_status_id == $status->arrangement)
                                <form action="{{ route('guest.return_products.store') }}" method="post">
                                    @csrf
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <div class="total">
                                            <button type="submit" style="border: none; background-color: white;">
                                                <h2 style="color:red;font-size: 12px;">{{__('profile.RETURN')}}</h2>
                                            </button>
                                        </div>
                                    </div>
                                    <input hidden value="{{ $order->id }}" name="return_order_id">
                                </form>
                            @endif
                        </div> --}}
                        <hr>
                    @empty
                        <h3 class="text-center">{{ __('profile.NO_REQUESTS_TO_SHOW') }}</h3>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-sm-12 col-xs-12">
        {{ $orders->links('pagination.custom_pagination') }}
        <style>
            .pagination {
                float: left;
                display: inline;
            }
        </style>
    </div>
@endsection
