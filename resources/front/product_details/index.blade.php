@extends('front.index')


@section('page_title', $product->name)

@section('meta_description', Str::limit($product->description, 155))
@section('front-section')

    <x-front.popup-component/>
    <!--=====================================================================-->
    <!--=====================================================================-->

    <x-front.alert type="success"/>
    <x-front.alert type="danger"/>

    <div class="product-details">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="road">
                        <ul>
                            <li><img src="images/home.png" alt=""></li>
                            <li>{{ __('general.MAIN') }}</li>
                            <li> |</li>
                            <li>{{ $product->parent->CurrentNameLang }}</li>
                            <li> |</li>
                            <li> {{ $product->CurrentNameLang }}</li>
                        </ul>
                    </div>
                </div>
                {{-- image lightbox --}}
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="mini-pics">
                                @forelse($product->images->take(4) as $image)
                                    <a class="fancybox" href="{{ asset('storage/' . $image->image) }}"
                                       data-fancybox-group="gallery"><img title="{{ $product->CurrentNameLang }}"
                                                                          class="img-reswponsive"
                                                                          src="{{ asset('storage/' . $image->image) }}"
                                                                          alt="">
                                    </a>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="big-pic">
                            <a class="fancybox" data-fancybox-group="gallery"><img
                                        title="{{ $product->CurrentNameLang }}"
                                        class="img-reswponsive" src="{{ $product->image_url }}" alt=""></a>
                        </div>

                    </div>


                </div>
                {{-- product info --}}
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="product-content">

                        <h5>{{ $product->parent->CurrentNameLang }}</h5>
                        <h3>{{ $product->CurrentNameLang }}</h3>
                        <p>
                            {!! translateWithHTMLTags($product->description) !!}
                        </p>
                        <div class="icons">
                            <form action="{{ route('wishlist_product_details', $product->id) }}" method="post">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button
                                        style=" background:{{ Auth::guard('web')->check() &&Auth::guard('web')->user()->wishlistProducts()->where('product_id', $product->id)->exists()? '#F55157': '#999dff' }}">
                                    <i class="fa fa-heart"></i></button>
                                <button><i class="fa-solid fa-arrow-right-arrow-left"></i></button>
                            </form>

                        </div>
                        <hr>

                        <div class="price">
                            <h2> {{ resolve('App\currency\Currency')->getCurrency($product->price) }}
                                @if (!empty($product->discount_price))
                                    <h5> {{ $product->discount_price }}</h5>
                                @endif
                            </h2>

                        </div>
                        <h1>{{ __('general.AVAILABLE') }} : <span> {{ $product->quantity }}
                                {{ __('general.PIECES') }}</span></h1>


                        <div class="actions">

                            <form action="{{route('cart.store')}}" method="post">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div style="display: flex; gap: 10px;">
                                    <div class="counter">
                                        <button class="p-qty-btn-minus" onclick="decreaseQuantity(event)">-</button>
                                        <input
                                                style="outline: none;
                                            width: 86px;
                                            height: 41px;
                                            border-radius: 8px;
                                            background-color: transparent;
                                            color: #3050AC;
                                            border: 1px solid #E8E8E8;
                                            text-align: center;
                                            display: inline-block;
                                            line-height: 40px;
                                            padding: 0px;"
                                                type="text" id="input-quantity-1" value="1" name="quantity">
                                        <button class="p-qty-btn-plus" onclick="increaseQuantity(event)">+</button>
                                    </div>

                                    <div class="buyFast">
                                        <button type="submit" name="action" value="addAndGoToCart"><i
                                                    class="fa fa-credit-card-alt"></i> {{ __('general.FAST_CHECKOUT') }}
                                        </button>
                                    </div>
                                </div>

                            </form>


                            <div class="addToCart">
                                <form style="display: contents;" id="addToCartForm" action="{{ route('cart.store') }}"
                                      method="post">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button onclick="Alert.success('','تمت اضافة المنتج في السلة',{displayDuration: 5000})"
                                            class="addToCart"><i class="fa fa-shopping-bag"></i>
                                        {{ __('general.ADD_TO_CARE') }}
                                    </button>

                                </form>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="clearfix"></div>
                {{-- التفاصيل --}}
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="allDetails">
                        <h3 class="head">{{ __('general.DETAILS') }}</h3>
                        <hr>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="overview">
                                <h3>{{ __('general.OVERVIEW') }}</h3>
                                {!! translateWithHTMLTags($product->description) !!}
                                {{--                                <p>{{ $product->description }}</p>--}}
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            @if ($product->features->count() > 0)
                                <div class="overview">
                                    <h3>{{ __('general.SPECIFICATIONS') }} </h3>
                                    <ul>
                                        @foreach ($product->features as $feature)
                                            <li>
                                                {{ $feature->feature_name }}
                                                <span>{{ $feature->feature_description }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=====================================================================-->
    {{-- منتجات متشابهة --}}
    <div class="products-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="head">
                        <h3>{{ __('general.SIMILAR_PRODUCTS') }}</h3>
                    </div>
                </div>
                @forelse ($relatedProducts as $product)
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="oneProduct">
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

                                        {{-- <li><a href=""><i class="far fa-heart"></i></a> </li> --}}
                                    </ul>
                                </div>
                                <div class="new">
                                    <span>{{ $product->availability->CurrentNameLang }}</span>
                                </div>

                                @if ($product->discount_price)
                                    <div style="top: 75px" class="discount">
                                        <span>
                                            {{ __('general.DISCOUNT') }}
                                            {{ $product->price }}
                                            %
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="content">
                                {{-- <h6>مستلزمات المطابخ الشيف</h6> --}}
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
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button
                                                onclick="Alert.success('','تمت اضافة المنتج في السلة',{displayDuration: 5000})"
                                                class="addToCart">
                                            <i class="fa fa-bag-shopping"></i> {{ __('general.ADD_TO_CART') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-danger">{{ __('general.PRODUCTS_NOT_EXIST') }}</p>
                @endforelse
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="seeMore">
                        <a
                                href="{{ route('front.categories', $product->parent->slug) }}">{{ __('general.VIEW_MORE_PRODUCTS') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function increaseQuantity(event) {
            event.preventDefault(); // Prevent default button behavior
            var input = document.getElementById('input-quantity-1');
            input.value = parseInt(input.value) + 1; // Increment value
        }

        function decreaseQuantity(event) {
            event.preventDefault(); // Prevent default button behavior
            var input = document.getElementById('input-quantity-1');
            if (parseInt(input.value) > 1) { // Ensure value doesn't go below 1
                input.value = parseInt(input.value) - 1; // Decrement value
            }
        }

        $(document).ready(function () {
            $('#show-comments').on('click', function (e) {
                e.preventDefault(); // Prevent the default behavior of the link
                $('#comments-container').toggle(); // Toggle display of comments container
                $('.see-more').hide(); // Hide the "عرض المزيد" link
            });
        });
    </script>



    @push('scripts')
        <script src="{{ asset('front/css/jquery.fancybox.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.fancybox').fancybox();
            });
        </script>

        <script>
            $('#show-comments').on('click', function (e) {
                e.preventDefault(); // Prevent the default behavior of the link
                $('#comments-container').toggle(); // Toggle display of comments container
                $('.see-more').hide(); // Hide the "عرض المزيد" link
            });
            })
            ;
        </script>
    @endpush

    @push('styles')
        <link rel="stylesheet" href="{{ asset('front/css/jquery.fancybox.css') }}">
    @endpush


    <style>
        .rate:not(:checked) > input {
            position: absolute;
            visibility: hidden;
        }

        .rate:not(:checked) > label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 30px;
            color: #ccc;
        }

        .rate:not(:checked) > label:before {
            content: '★ ';
        }

        .rate > input:checked ~ label {
            color: #ffc700;
        }

        .rate:not(:checked) > label:hover,
        .rate:not(:checked) > label:hover ~ label {
            color: #deb217;
        }

        .rate > input:checked + label:hover,
        .rate > input:checked + label:hover ~ label,
        .rate > input:checked ~ label:hover,
        .rate > input:checked ~ label:hover ~ label,
        .rate > label:hover ~ input:checked ~ label {
            color: #c59b08;
        }
    </style>

@endsection
