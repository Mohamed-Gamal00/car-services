@extends('front.index')


@section('page_title', 'الرئيسية')
{{-- @section('meta_description', Str::limit($headerText->first()->description, 155)) --}}

@section('front-section')
    {{-- Popup Add To Cart --}}
    <x-front.popup-component/>

    <div class="homePage">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="menu">
                        <ul>
                            @forelse ($categories as $category)
                                <li>
                                    <a href="{{ route('front.products') }}?category_id={{ $category->id }}">{{ $category->CurrentNameLang }}
                                        <i class="fa fa-angle-left"></i></a></li>
                            @empty
                                <p class="text-danger">لا يوجد اقسام</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="mainSlider">
                        @forelse ($headerImages as $headerimage)
                            <div class="item">
                                <a href="{{ $headerimage->image_link }}">
                                    <img src="{{ asset('storage/' . $headerimage->header_image) }}" alt="">
                                </a>
                            </div>
                        @empty
                            <p class="text-danger">not found</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- احدث الخدمات --}}
    <div class="products-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="head">
                        <h3>{{ __('general.LATEST_PRODUCT') }}</h3>
                    </div>
                </div>
                @forelse ($products as $product)
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="oneProduct" style="float: none">
                            <div class="pic">
                                <div>
                                    <img style="width: 100%;height: 200px;" class="pro" src="{{ $product->image_url }}"
                                         alt="">

                                </div>
                                <div class="icons">
                                    <ul>
                                        <a href="{{ route('product.details', $product->slug) }}">
                                            <li><i class="fa fa-eye"></i></li>
                                        </a>
                                        <x-add-to-wish-list :productId="$product->id"/>
                                    </ul>
                                </div>
                                @if ($product->quantity == 0)
                                    <div class="discount">
                                        <span>
                                            {{ __('general.NOT_AVAILABLE') }}
                                        </span>
                                    </div>
                                @else
                                    <div class="new">
                                        <span>{{ $product->availability->CurrentNameLang }}</span>
                                    </div>
                                    @if ($product->discount_price)
                                        <div style="top: 75px" class="discount">
                                            <span>
                                                خصم
                                                {{ $product->price }}
                                                %
                                            </span>
                                        </div>
                                    @endif
                                @endif


                            </div>
                            <div class="content">
                                <h6>{{ $product->parent->CurrentNameLang }}</h6>
                                <a href="">{{ $product->CurrentNameLang }}</a>
                                <div class="price">
                                    <h6> {{ resolve('App\currency\Currency')->getCurrency($product->price) }}</h6>
                                    <h5>
                                        <span>
                                            @if ($product->discount_price)
                                                {{ resolve('App\currency\Currency')->getCurrency($product->discount_price) ?? '' }}
                                            @endif
                                        </span>
                                    </h5>

                                </div>
                                <div class="addToCart">
                                    <form id="addToCartForm" action="{{ route('cart.store') }}" method="post">
                                        @csrf
                                        @method('post')
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button {{ $product->quantity == 0 ? 'disabled' : '' }}
                                                onclick="Alert.success('','تمت اضافة المنتج في السلة',{displayDuration: 5000})"
                                                class="addToCart">
                                            <i class="fa fa-bag-shopping"></i> {{ __('general.ADD_TO_CARE') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>no product exist</p>
                @endforelse

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="seeMore">
                        <a href="{{ route('latest_products') }}">{{ __('general.SEE_MORE') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===================================== البنرات ================================-->
    <div class="banners-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="pic">
                        <img src="{{ asset('storage/' . $banners_result['banner1']) }}" alt="">
                    </div>
                    <div class="pic">
                        <img src="{{ asset('storage/' . $banners_result['banner2']) }}" alt="">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="pic">
                        <img class="cover" src="{{ asset('storage/' . $banners_result['banner3']) }}" alt="">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="pic">
                        <img src="{{ asset('storage/' . $banners_result['banner4']) }}" alt="">
                    </div>
                    <div class="pic">
                        <img src="{{ asset('storage/' . $banners_result['banner5']) }}" alt="">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--=================================== الاكثر مبيعا ==============================-->
    <div class="products-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="head">
                        <h3>{{ __('general.TPO_SELLING') }}</h3>
                    </div>
                </div>
                @forelse ($topSellingProducts as $product)
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="oneProduct" style="float: none">
                            <div class="pic">
                                <div>
                                    <img style="width: 100%;height: 200px;" class="pro" src="{{ $product->image_url }}"
                                         alt="">

                                </div>
                                <div class="icons">
                                    <ul>
                                        <a href="{{ route('product.details', $product->slug) }}">
                                            <li><i class="fa fa-eye"></i></li>
                                        </a>
                                        <x-add-to-wish-list :productId="$product->id"/>
                                    </ul>
                                </div>
                                @if ($product->quantity == 0)
                                    <div class="discount">
                                        <span>
                                            {{ __('general.NOT_AVAILABLE') }}
                                        </span>
                                    </div>
                                @else
                                    <div class="new">
                                        <span>{{ $product->availability->CurrentNameLang }}</span>
                                    </div>
                                    @if ($product->discount_price)
                                        <div style="top: 75px" class="discount">
                                            <span>
                                                خصم
                                                {{ $product->price }}
                                                %
                                            </span>
                                        </div>
                                    @endif
                                @endif


                            </div>
                            <div class="content">
                                <h6>{{ $product->parent->CurrentNameLang }}</h6>
                                <a href="">{{ $product->CurrentNameLang }}</a>
                                <div class="price">
                                    <h6> {{ resolve('App\currency\Currency')->getCurrency($product->price) }}</h6>
                                    <h5>
                                        <span>
                                            @if ($product->discount_price)
                                                {{ resolve('App\currency\Currency')->getCurrency($product->discount_price) ?? '' }}
                                            @endif
                                        </span>
                                    </h5>

                                </div>
                                <div class="addToCart">
                                    <form id="addToCartForm" action="{{ route('cart.store') }}" method="post">
                                        @csrf
                                        @method('post')
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button {{ $product->quantity == 0 ? 'disabled' : '' }}
                                                onclick="Alert.success('','تمت اضافة المنتج في السلة',{displayDuration: 5000})"
                                                class="addToCart">
                                            <i class="fa fa-bag-shopping"></i> {{ __('general.ADD_TO_CARE') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-danger">no product exist</p>
                @endforelse
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="seeMore">
                        <a href="{{ route('top_products') }}">{{ __('general.SEE_MORE') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===================================== عروض و تخفيضات ================================-->
    <div class="products-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="head">
                        <h3>{{ __('general.OFFERS') }}</h3>
                    </div>
                </div>
                @forelse ($OffersProducts as $product)
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="oneProduct" style="float: none">
                            <div class="pic">
                                <div>
                                    <img style="width: 100%;height: 200px;" class="pro" src="{{ $product->image_url }}"
                                         alt="">

                                </div>
                                <div class="icons">
                                    <ul>
                                        <a href="{{ route('product.details', $product->slug) }}">
                                            <li><i class="fa fa-eye"></i></li>
                                        </a>
                                        <x-add-to-wish-list :productId="$product->id"/>
                                    </ul>
                                </div>
                                @if ($product->quantity == 0)
                                    <div class="discount">
                                        <span>
                                            {{ __('general.NOT_AVAILABLE') }}
                                        </span>
                                    </div>
                                @else
                                    <div class="new">
                                        <span>{{ $product->availability->CurrentNameLang }}</span>
                                    </div>
                                    @if ($product->discount_price)
                                        <div style="top: 75px" class="discount">
                                            <span>
                                                خصم
                                                {{ $product->price }}
                                                %
                                            </span>
                                        </div>
                                    @endif
                                @endif


                            </div>
                            <div class="content">
                                <h6>{{ $product->parent->CurrentNameLang }}</h6>
                                <a href="">{{ $product->CurrentNameLang }}</a>
                                <div class="price">
                                    <h6> {{ resolve('App\currency\Currency')->getCurrency($product->price) }}</h6>
                                    <h5>
                                        <span>
                                            @if ($product->discount_price)
                                                {{ resolve('App\currency\Currency')->getCurrency($product->discount_price) ?? '' }}
                                            @endif
                                        </span>
                                    </h5>

                                </div>
                                <div class="addToCart">
                                    <form id="addToCartForm" action="{{ route('cart.store') }}" method="post">
                                        @csrf
                                        @method('post')
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button {{ $product->quantity == 0 ? 'disabled' : '' }}
                                                onclick="Alert.success('','تمت اضافة المنتج في السلة',{displayDuration: 5000})"
                                                class="addToCart">
                                            <i class="fa fa-bag-shopping"></i> {{ __('general.ADD_TO_CARE') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-danger">no product exist</p>
                @endforelse
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="seeMore">
                        <a href="{{ route('offer_products') }}">{{ __('general.SEE_MORE') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=====================================  بنرات عروض  ================================-->

    <div class="banner-horizontal-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="pic">
                        <img src="{{ asset('storage/' . $banners_result['discount1']) }}" alt="">
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="pic">
                        <img src="{{ asset('storage/' . $banners_result['discount2']) }}" alt="">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--===================================== الشركات ================================-->
    <div class="brands-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="head">
                        <h3> {{ __('general.BRANDS') }}</h3>
                    </div>
                </div>
                @forelse ($companies as $company)
                    <div class="col-md-2 col-sm-2 col-xs-6">
                        <div class="pic">
                            <img src="{{ asset('storage/' . $company->image) }}" alt="">
                            <a href="{{ route('front.products') }}">{{ __('general.SHOW_PRODUCTS') }}</a>
                        </div>

                    </div>
                @empty
                    <p>لا يوجد ماركات تجارية</p>
                @endforelse
            </div>
        </div>
    </div>
    <!--=====================================================================-->

@endsection
