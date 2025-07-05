@extends('front.profile.index')

@section('page_title', 'المفضله')
@section('breadcrumb')
    <li> المفضلة</li>
@endsection

@section('user_section')
    <x-front.popup-component/>
    <div>
        <div id="acc-1" class="tab-pane fade in active">
            <div class="products-section">
                <div class="container-fluid">
                    <div class="row">
                        @forelse ($products as $product)
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="oneProduct">
                                    <div class="pic">
                                        <div>
                                            <img style="width: 100%;height: 200px;" class="pro"
                                                 src="{{ $product->image_url }}" alt="">

                                        </div>
                                        <div class="icons">
                                            <ul>
                                                <a href="{{ route('product.details', $product->slug) }}">
                                                    <li><i class="fa fa-eye"></i></li>
                                                </a>
                                                <x-add-to-wish-list :productId="$product->id"/>
                                            </ul>
                                        </div>
                                        <div class="new">
                                            <span>{{ $product->availability->CurrentNameLang }}</span>
                                        </div>

                                        <div style="top: 75px" class="discount">
                                            <span>
                                                خصم
                                                {{ $product->price }}
                                                %
                                            </span>
                                        </div>
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
                            <p class="text-danger">{{ __('profile.PRODUCTS_NOT_EXIST') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div><!--One-->
    </div>
    {{ $products->links('pagination.custom_pagination') }}
    <style>
        .pagination {
            float: left;
        }
    </style>

@endsection
