@forelse($products as $product)
    <div class="col-md-4 col-sm-6 col-xs-6">
        <div class="pro">
            <div class="discount">خصم 20%</div>
            <img class="pic" src="{{$product->image_url}}" alt="">
            <h3>{{$product->parent->name}}</h3>
            <a href="">{{$product->name}}</a>
            <h5>{{$product->price}} ر.س
                <span>@if($product->discount_price)
                        {{("$product->discount_price ر.س ") ?? ''}}
                    @endif </span>
            </h5>
            <div class="more">
                <button class="addToCart"><img
                        src="{{asset('front/images/cart-w-icon.svg')}}"> اضف
                                                                         للسلة
                </button>
                <form id="wishlistForm"
                      action="{{ route('add.wishlist', $product->id) }}"
                      method="post">
                    @csrf
                    <input type="hidden" name="product_id"
                           value="{{ $product->id }}">
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
{{$products->links('pagination.custom_pagination')}}
<style>
    .pagination {
        float: left;
    }
</style>
