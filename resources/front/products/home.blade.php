@extends('front.index')


@section('front-section')

    <style>
        .intro {
            background-image: url("{{asset('storage/'. $mainDesign->image) }}");
            background-position: center center;
            background-size: cover;
            padding: 180px 0px;
            background-repeat: no-repeat;
        }
    </style>

    <div class="intro">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="main-slider">
                        @forelse($headerText as $text)
                            <div class="item">
                                <div class="content">
                                    <h4>{{$text->title}}</h4>
                                    <p>{{Str::limit($text->description, 400)}}</p>
                                    <a href="">اعرف أكثر</a>
                                </div>
                            </div>
                        @empty
                            <div></div>
                        @endforelse

                    </div>


                </div>
            </div>
        </div>
    </div>
    <!--=====================================================================-->
    <div class="shop-category">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="head-bar">
                        <h3>تسوق حسب الفئات <a href="">عرض الكل</a></h3>
                    </div>
                </div>
                @forelse($categories as $category)
                    <div class="col-md-2 col-sm-3 col-xs-6">
                        <div class="one">
                            <div class="pic">
                                <img src="{{$category->image_url}}" alt="">
                            </div>
                            <a href="">{{$category->name}}</a>
                        </div>

                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
    <!--=====================================================================-->
    <div class="product-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="head">
                        <h3>المضاف حديثآ</h3>
                        <h5>تسوق احدث الخدمات المضافة حديثآ</h5>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="recentlyAdded-slider">
                        @forelse($products as $product)
                            <div class="item">
                                <div class="pro">
                                    <div class="discount">خصم 20%</div>
                                    <img class="pic" src="{{$product->image_url}}" alt="">
                                    <h3>{{$product->parent->name}}</h3>
                                    <a href="">{{$product->name}}</a>
                                    <h5>{{$product->price}} ر.س <span> {{$product->discount_price}} ر.س</span></h5>
                                    <div class="more">
                                        <button class="addToCart"><img src="{{asset('front/images/cart-w-icon.svg')}}">
                                            اضف
                                            للسلة
                                        </button>
                                        <button class="like"><img src="{{asset('front/images/heart-icon.svg')}}">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty

                        @endforelse

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--=====================================================================-->
    <div class="banner-section">
        <div class="container">
            <div class="row">
                @forelse($designs as $design)
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="pic">
                            <img class="img-responsive" src="{{$design->image_url}}" alt="">
                        </div>
                    </div>
                @empty

                @endforelse
            </div>
        </div>
    </div>
    <!--=====================================================================-->
    <div class="product-section">
        <div class="container">
            <div class="row">
                @forelse($specialProducts as $product)
                    <div class="col-md-3 col-sm-3 col-xs-6">

                        <div class="pro">
                            <div class="discount">خصم 20%</div>
                            <img class="pic" src="{{$product->image_url}}" alt="">
                            <h3>{{$product->parent->name}}</h3>
                            <a href="">{{$product->name}}</a>
                            <h5>{{$product->price}} ر.س <span> {{$product->discount_price}} ر.س</span></h5>
                            <div class="more">
                                <button class="addToCart"><img src="{{asset('front/images/cart-w-icon.svg')}}"> اضف
                                    للسلة
                                </button>
                                <button class="like"><i class="far fa-heart"></i></button>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
    <!--=====================================================================-->
    <div class="banner-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="pic">
                        <img class="img-responsive" src="{{$mainDesign->image_url}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=====================================================================-->
    <div class="product-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="head-section">
                        <div class="col-md-8 col-sm-8 col-xs-8">
                            <h3>الخدمات المميزة <span>تسوق اكثر  الخدمات مبيعآ </span></h3>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <a href="">عرض الكل</a>
                        </div>
                    </div>
                </div>
                @forelse($specialProducts as $product)
                    <div class="col-md-3 col-sm-3 col-xs-6">

                        <div class="pro">
                            <div class="discount">خصم 20%</div>
                            <img class="pic" src="{{$product->image_url}}" alt="">
                            <h3>{{$product->parent->name}}</h3>
                            <a href="">{{$product->name}}</a>
                            <h5>{{$product->price}} ر.س <span> {{$product->discount_price}} ر.س</span></h5>
                            <div class="more">
                                <button class="addToCart"><img src="{{asset('front/images/cart-w-icon.svg')}}"> اضف
                                    للسلة
                                </button>
                                <button class="like"><i class="far fa-heart"></i></button>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
    <!--=====================================================================-->
    <div class="brands-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="head">
                        <h3>الماركات التجارية </h3>
                        <h5>يمكنك التسوق من خلال اختيار الماركة</h5>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="brands-slider">
                        @forelse($companies as $company)

                            <div class="item">
                                <div class="brand">
                                    <img src="{{asset('storage/' . $company->image)}}" alt="">
                                </div>
                            </div>

                        @empty @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
