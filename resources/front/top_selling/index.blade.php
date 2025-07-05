@php use App\currency\Currency; @endphp
@extends('front.index')

@section('page_title', 'الأكثر مبيعا')
@section('meta_description',
    'اكتشف الخدمات الأكثر مبيعًا: تسوق أفضل الخدمات التي حظيت بشعبية كبيرة لدى عملائنا.
    استمتع بتجربة تسوق مثالية مع تشكيلتنا المميزة التي تتضمن كل ما تحتاجه لتلبية احتياجاتك بأعلى جودة وأفضل الأسعار. اكتشف
    الآن وانضم إلى قائمة عملائنا المميزين.')

@section('front-section')
    {{-- Popup Add To Cart --}}
    <x-front.popup-component/>
    <div class="products-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="road" style="margin-bottom: 30px">
                        <ul>
                            <li><img src="images/home.png" alt=""></li>
                            <li>{{ __('general.MAIN') }}</li>
                            <li> |</li>
                            <li>{{ __('general.TPO_SELLING') }}</li>
                        </ul>
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
            </div>
            {{ $topSellingProducts->withQueryString()->links('pagination.custom_pagination') }}

        </div>
    </div>
@endsection
