@extends('front.profile.index')

@section('page_title', 'الارجاع')
@push('styles')
    <link rel="stylesheet" href="{{ asset('front/css/old-style.css') }}">
@endpush

@section('breadcrumb')
    <li> {{ __('profile.RETURNS') }} </li>
@endsection

@section('user_section')

    <div class="account-content">
        <div id="Orders" class="tab-pane fade in active">
            <div class="col-md-12 col-sm-8 col-xs-12">
                <div class="ordersContent">
                    <h3>{{ __('profile.RETURNS') }}</h3>
                    @forelse ($products as $order)
                        @foreach ($order->products as $item)
                            <div class="one-order">
                                <div class="pic">
                                    <img height="100px" src="{{ $item->image_url }}" alt="">
                                </div>
                                <div class="data">
                                    <h5>{{ $item->parent->name }}</h5>
                                    <h2>{{ $item->name }}</h2>
                                    <span>{{ resolve('App\currency\Currency')->getCurrency($item->price) }}</span>
                                </div>
                            </div>
                        @endforeach
                    @empty
                        <div class="returnsContent">
                            <div class="data">
                                <img src="{{ asset('front/images/restore.png') }}" alt="">
                                <h4>{{__('profile.NO_RETURN_REQUESTS')}}</h4>
                                <p>{{__('profile.NO_RETURN_REQUESTS_INFO')}}/p>
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
