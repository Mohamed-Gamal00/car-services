@extends('front.profile.index')

@section('page_title', 'الطلبات')
<link rel="stylesheet" href="{{ asset('front/css/old-style.css') }}">

@section('breadcrumb')
    <li> {{ __('profile.ORDERS') }} </li>
@endsection
@section('user_section')
    <x-front.alert type="danger"/>
    <x-front.alert type="success"/>
    <div class="account-content">
        <div id="Orders" class="tab-pane fade in active">
            <div class="col-md-12 col-sm-8 col-xs-12">
                <div class="ordersContent">
                    <h3>{{ __('profile.ORDERS') }}</h3>
                    @forelse ($orders as $order)
                        <div class="statues">
                            <ul>
                                @forelse($orderStatus as $status)
                                    <li style="font-size: 12px;"
                                        class="{{ $order->order_status_id == $status->id ? 'active' : '' }}"><i
                                                class="fa fa-check-circle"></i>{{ $status->CurrentNameLang }}</li>
                                @empty
                                @endforelse

                                <a href="{{ route('user.orders', $order->number) }}">
                                    @csrf
                                    @method('delete')
                                    <button style="border: none; background: none ;float: inline-end;">
                                        <li style="color: forestgreen;font-size: 12px;" class=""><i
                                                    class="fa fa-eye"></i>{{ __('profile.VIEW_ORDER') }}
                                        </li>
                                    </button>
                                    <input hidden name="order_id" value="{{ $order->id }}">
                                </a>
                            </ul>
                        </div>
                        <div class="one-order">
                            <div class="pic">
                                <img height="" src="{{ asset('front/images/order_image.png') }}" alt="">
                            </div>
                            <div class="data">
                                <div class="data">
                                    <h5> {{__('profile.ORDER_NUMBER')}}: #{{ $order->number }} </h5>
                                    @if ($order->shipping_price)
                                        <h2>
                                            {{__('profile.TOTAL')}} :
                                            <span>{{ resolve('App\currency\Currency')->getCurrency($order->total_price + intval($order->shipping_price)) }}</span>
                                        </h2>
                                    @else
                                        <h2>
                                            {{__('profile.TOTAL')}} :
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
                                    <form action="{{ route('user.return_products.store') }}" method="post">
                                        @csrf

                                        <button type="submit"
                                                style="border: none; background-color: white;">
                                            <h2 style="color:red;font-size: 13px;">ارجاع</h2>
                                        </button>

                                        <input hidden value="{{ $order->id }}" name="return_order_id">
                                    </form>
                                @endif
                            </div>

                        </div>
                        <hr>
                    @empty
                        <h3 class="text-center">{{ __('profile.NO_REQUESTS_TO_SHOW') }}</h3>
                    @endforelse
                </div>
            </div>
            {{-- <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="all-details">
                    <h4>{{__('profile.ORDER_SUMMARY')}}</h4>
                    <p>{{__('profile.PRODUCTS')}} <span> 400 رس</span></p>
                    <p>{{__('profile.DISCOUNT_CODE')}}<span> 200 رس</span></p>
                    <hr>
                    <h2>{{__('profile.TOTAL')}}<span>700 رس</span></h2>
                    
                </div>
            </div> --}}


        </div>
    </div>
@endsection
