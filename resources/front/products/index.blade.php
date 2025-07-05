@extends('front.index')

@section('meta_description', Str::limit($settings->title, 155))

@section('page_title', 'كل الخدمات')
@section('front-section')
    <!--=====================================================================-->

    <x-front.popup-component/>

    <div class="products-section grid" id="products">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="road">
                        <ul>
                            <li><img src="images/home.png" alt=""></li>
                            <li>{{ __('general.MAIN') }}</li>
                            <li> |</li>
                            <li>الخدمات</li>
                        </ul>
                    </div>
                </div>
                {{-- فلتر --}}
                <form id="price-filter-form" action="{{ route('front.products') }}" method="get">

                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="head">
                            <h4>{{ __('general.RESULT_FILTER') }}</h4>
                        </div>
                        <div class="filter">

                            {{-- الاقسام --}}
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button class="accordion {{ !is_null(request()->category_id) ? 'active' : '' }}"
                                        type="reset"> {{ __('general.CATEGORY') }}
                                </button>
                                <div class="panel"
                                     style="{{ !is_null(request()->category_id) ? 'max-height: max-content;' : '' }}">
                                    <label class="bg">{{ __('general.ALL') }}
                                        <input type="radio" name="category_id"
                                               {{ request()->category_id == 'all_categories' || is_null(request()->category_id) ? 'checked' : '' }}
                                               value="all_categories">
                                        <span class="checkmark"></span>
                                        <span class="num"> ({{ $categories->count() }})</span>
                                    </label>
                                    @forelse($categories as $category)
                                        <label class="bg">({{ $category->CurrentNameLang }})
                                            <input type="radio" name="category_id"
                                                   {{ $category->id == request()->category_id ? 'checked' : '' }}
                                                   value="{{ $category->id }}">
                                            <span class="checkmark"></span>
                                            <span class="num"> ({{ $category->products_count }})</span>
                                        </label>
                                    @empty
                                    @endforelse
                                </div>
                            </div>

                            {{-- الماركة --}}
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button class="accordion {{ !is_null(request()->company_id) ? 'active' : '' }}"
                                        type="reset"> {{ __('general.BRAND') }}
                                </button>
                                <div class="panel"
                                     style="{{ !is_null(request()->company_id) ? 'max-height: max-content;' : '' }}">
                                    @forelse($companies as $company)
                                        <label class="bg">({{ $company->CurrentNameLang }})
                                            <input type="radio" name="company_id" value="{{ $company->id }}"
                                                    {{ $company->id == request()->company_id ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                            <span class="num"> ({{ $company->products_count }})</span>
                                        </label>
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                            {{-- السعر --}}
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button
                                        class="accordion {{ !is_null(request()->price_min) || !is_null(request()->price_max) ? 'active' : '' }}"
                                        type="reset"> {{ __('general.PRICE') }}
                                </button>
                                <div class="panel"
                                     style="{{ !is_null(request()->price_min) || !is_null(request()->price_max) ? 'max-height: max-content;' : '' }}">
                                    <input type="number" value="{{ request()->price_min }}" name="price_min"
                                           class="price" placeholder="{{ __('general.FROM') }}">
                                    <input type="number" value="{{ request()->price_max }}" name="price_max"
                                           class="price" placeholder="{{ __('general.TO') }}">
                                </div>
                            </div>
                            {{-- ترتيب حسب --}}
                            {{-- <div class="col-md-12 col-sm-12 col-xs-12">
                                <button class="accordion {{ !is_null(request()->price) ? 'active' : '' }}" type="reset">
                                    ترتيب حسب
                                </button>

                                <div class="panel"
                                    style="{{ !is_null(request()->price) ? 'max-height: max-content;' : '' }}">

                                    <label class="bg">الكل
                                        <input type="radio" value="all" name="price"
                                            {{ request()->price == 'all' || is_null(request()->price) ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="bg">من الأعلى للأقل
                                        <input type="radio" value="high_to_low" name="price"
                                            {{ request()->price == 'high_to_low' ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="bg">من الأقل للأعلى
                                        <input type="radio" value="low_to_high" name="price"
                                            {{ request()->price == 'low_to_high' ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div> --}}
                            {{-- فلترة --}}
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="reset">
                                    <button type="submit">{{ __('general.RESET') }}</button>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="pic">
                                    <img src="images/banner-4.png" alt="">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="products-results">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="sorting">
                                    <div class="buttons">
                                        <button id="gridView"><i class="fa fa-grip"></i></button>
                                        <button id="listView"><i class="fa fa-bars"></i></button>
                                    </div>
                                    <label>{{ __('general.SORT_BY') }}</label>
                                    <select name="price" id="price-select">
                                        <option value="high_to_low"
                                                {{ request()->price == 'high_to_low' ? 'selected' : '' }}>
                                            {{ __('general.HIGHEST_FIRST') }}
                                        </option>
                                        <option value="low_to_high"
                                                {{ request()->price == 'low_to_high' ? 'selected' : '' }}>
                                            {{ __('general.LOWEST_FIRST') }}
                                        </option>
                                    </select>

                                    <script>
                                        document.getElementById('price-select').addEventListener('change', function () {
                                            document.getElementById('price-filter-form').submit();
                                        });
                                    </script>

                                </div>
                            </div>
                            <x-front.alert type="info"/>
                            @forelse($products as $product)
                                <div class="oneProduct">
                                    <div class="pic">
                                        <div>
                                            <img style="width: 100%;height: 200px;" class="pro"
                                                 src="{{ $product->image_url }}"
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

                            @empty

                                <h3 style="margin: 15px">{{ __('general.PRODUCTS_NOT_EXIST') }}</h3>
                            @endforelse

                        </div>
                        {{ $products->withQueryString()->links('pagination.custom_pagination') }}
                    </div>

                </form>
            </div>
        </div>
        {{-- الخدمات --}}
    </div>


    <style>
        .pagination {
            float: left;
        }
    </style>
@endsection
