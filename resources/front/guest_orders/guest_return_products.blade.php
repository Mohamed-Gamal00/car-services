@extends('front.profile.index')

@section('page_title', 'الارجاع')
@push('styles')
    <link rel="stylesheet" href="{{ asset('front/css/old-style.css') }}">
@endpush


@section('user_section')
    <div class="account-content">
        <div id="Orders" class="tab-pane fade in active">
            <div class="col-md-12 col-sm-8 col-xs-12">
                <div class="ordersContent">
                    <h3>{{ __('profile.RETURNS') }}</h3>
                    @forelse ($products as $order)
                        {{-- <div class="statues">
                            <ul>
                                @forelse($orderStatus as $status)
                                    <li class="{{ $order->order_status_id == $status->id ? 'active' : '' }}"><i
                                            class="fa fa-check-circle"></i>{{ $status->CurrentNameLang }}</li>
                                @empty
                                @endforelse
                            </ul>
                        </div> --}}
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
                            </div>
                        </div>
                        <hr>
                    @empty
                        <div class="returnsContent">
                            <div class="data">
                                <img src="{{ asset('front/images/restore.png') }}" alt="">
                                <h4>{{ __('profile.NO_RETURN_REQUESTS') }}</h4>
                                <p>{{ __('profile.NO_RETURN_REQUESTS_INFO') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 col-sm-12 col-xs-12">
        {{ $products->links('pagination.custom_pagination') }}
        <style>
            .pagination {
                float: left;
                display: inline;
            }
        </style>
    </div>
@endsection
