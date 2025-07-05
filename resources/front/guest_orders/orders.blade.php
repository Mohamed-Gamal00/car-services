@extends('front.profile.index')

@section('page_title', 'الطلبات')
@push('styles')
    <link rel="stylesheet" href="{{ asset('front/css/old-style.css') }}">
@endpush

@section('user_section')
    <x-front.alert type="danger"/>
    <x-front.alert type="success"/>
    <div class="account-content">
        <div id="acc-2" class="tab-pane fade in">
            <div class="col-md-8 col-sm-12 col-xs-12">
                @forelse($order->products as $product)
                    <div class="ordersContent" style="margin-bottom: 6px;">
                        <div class="statues">
                            <ul>
                                @forelse($orderStatus as $status)
                                    <li style="font-size: 12px;"
                                        class="{{ $order->order_status_id == $status->id ? 'active' : '' }}"><i
                                                class="fa fa-check-circle"></i>{{ $status->CurrentNameLang }}</li>
                                @empty
                                @endforelse
                                @if (isset($order->orderStatus->arrangement) && $order->orderStatus->arrangement <= 1)
                                    @if ($order->payment_status == 'paid')
                                        <li style="color: green; font-size: 12px; float: inline-end;" class="">
                                            <i class="fa fa-check"></i> {{ __('profile.PAID') }}
                                        </li>
                                    @else
                                        <form style="display: inline;" action="{{ route('order.delete') }}"
                                              method="post">
                                            @csrf
                                            @method('delete')
                                            <button style="border: none; background: none; float: inline-end;">
                                                <li style="color: red; font-size: 12px;" class="">
                                                    <i class="fa fa-ban"></i> {{ __('profile.Cancel_Order') }}
                                                </li>
                                            </button>
                                            <input hidden name="product_id" value="{{ $product->id }}">
                                            <input hidden name="order_number" value="{{ $order->number }}">
                                        </form>
                                    @endif
                                @else
                                    <li style="color: green; font-size: 12px; float: inline-end;" class="">
                                        <i class="fa fa-check"></i> {{ __('profile.PAID') }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="one-order">
                            <div class="pic">
                                <img height="100px" src="{{ $product->orderItems->first()->product->image_url }}"
                                     alt="">
                            </div>
                            <div class="data">
                                <h5>{{ $product->orderItems->first()->product->parent->name }}</h5>
                                <h2>{{ $product->orderItems->first()->product->name }}</h2>
                                <h2>{{ __('profile.QUANTITY') }} : {{ $product->order_items->quantity }}</h2>
                                <p> {{ $product->orderItems->first()->product->price }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="total">
                            <h3>{{ __('profile.NO_REQUESTS_TO_SHOW') }}</h3>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="all-details">
                    <h2>{{ __('profile.ORDER_SUMMARY') }}</h2>
                    <hr>
                    <h2> {{ __('profile.TOTAL_PRODUCTS') }}<span>{{ $order->orderItems->sum('quantity') }}</span></h2>
                    <hr>
                    <h2>
                        {{ __('profile.NAME') }}
                        <span>{{ $order->addresses->first()->first_name . ' ' . $order->addresses->first()->last_name }}</span>
                    </h2>
                    <hr>
                    <h2>
                        {{ __('profile.ADDRESS') }}<span>{{ $order->addresses->first()->address }}</span>
                    </h2>
                    <hr>
                    <h2> {{ __('profile.SHIPPING') }}
                        {{-- <span>{{ $order->shipping_price ?? 'الاستلام من المتجر' }}</span> --}}
                        <span>{{ $order->shipping_price ?? __('profile.STORE_PICKUP') }}</span>
                    </h2>
                    <hr>

                    @if ($order->shipping_price)
                        <h2>{{ __('profile.TOTAL') }}
                            <span>{{ resolve('App\currency\Currency')->getCurrency($order->total_price + intval($order->shipping_price)) }}</span>
                        </h2>
                    @else
                        <h2> {{ __('profile.TOTAL') }}
                            <span>{{ resolve('App\currency\Currency')->getCurrency($order->total_price) }}</span>
                        </h2>
                    @endif

                </div>
            </div>


        </div>


    </div><!--Two-->
@endsection
