@php use App\currency\Currency; @endphp
@extends('front.index')

@section('meta_description', "تسوق الخدمات المميزة: استمتع بتجربة تسوق فريدة من نوعها مع مجموعة متنوعة ومختارة بعناية من الخدمات الفريدة والراقية. اكتشف مجموعتنا الواسعة من الخدمات عالية الجودة والتصاميم الرائعة التي تلبي جميع احتياجاتك. ")


@section('front-section')
    {{--Popup Add To Cart--}}
    <x-front.popup-component/>
    <div class="product-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="head-section">
                        <div class="col-md-8 col-sm-8 col-xs-8">
                            <h3>الاكثر مبيعآ <span>تسوق اكثر  الخدمات مبيعآ </span></h3>
                        </div>

                    </div>
                </div>
                @forelse($specialProducts as $product)
                    <div class="col-md-3 col-sm-3 col-xs-6">

                        <div class="pro">
                            @if(!empty($product->discount_price) && $product->discount_price > 0)
                                <div class="discount">
                                    خصم {{ round(($product->discount_price - $product->price) / $product->discount_price * 100) }}
                                    %
                                </div>
                            @endif

                            @if($product->product_availability_id)
                                <span class="sp"
                                      style="background-color:#0050bf">{{$product->availability->name}}</span>
                            @endif
                            <a href="{{route('product.details', $product->slug)}}"> <img class="pic"
                                                                                         src="{{$product->image_url}}"
                                                                                         alt=""></a>
                            <h3>{{$product->parent->name}}</h3>
                            <a href="">{{$product->name}}</a>
                            <h5> {{ resolve('App\currency\Currency')->getCurrency($product->price)}}
                                <span>@if($product->discount_price)
                                        {{(resolve('App\currency\Currency')->getCurrency($product->discount_price)) ?? ''}}
                                    @endif </span>
                            </h5>
                            <div class="more">
                                <form id="addToCartForm" action="{{ route('cart.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button
                                            onclick="Alert.success('','تمت اضافة المنتج في السلة',{displayDuration: 5000})"
                                            class="addToCart">
                                        <img src="{{ asset('front/images/cart-w-icon.svg') }}"> اضف للسلة
                                    </button>
                                </form>
                                <form id="wishlistForm" action="{{ route('add.wishlist', $product->id) }}"
                                      method="post">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button
                                            style="color: white; background:{{Auth::guard('web')->check() &&Auth::guard('web')->user()->wishlistProducts()->where('product_id', $product->id)->exists() ? '#F55157': '#ffd5d5'}}"
                                            class="like">
                                        <i class="fa fa-heart"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
            {{$specialProducts->withQueryString()->links('pagination.custom_pagination')}}
        </div>

    </div>
    <style>
        .pagination {
            float: left;
        }
    </style>
@endsection
