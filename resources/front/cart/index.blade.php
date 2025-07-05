@extends('front.index')

@section('page_title', 'السلة')

@section('front-section')
    <!--=====================================================================-->
    <div class="cartPage">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="road">
                        <ul>
                            <li><img src="images/home.png" alt=""></li>
                            <li>{{ __('general.MAIN') }}</li>
                            <li> |</li>
                            <li> {{ __('cart.CART') }} </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="cartContent">
                        <h3>{{ __('cart.CART') }}</h3>
                        @forelse ($cart->get() as $product)
                            <div class="one-cart product" id="{{ $product->id }}">
                                <div class="pic">
                                    <a href="{{ route('product.details', $product->product->slug) }}"> <img
                                                height="100px"
                                                class="pic" src="{{ $product->product->image_url }}" alt=""></a>
                                </div>
                                <div class="data">
                                    <h5>{{ $product->product->parent->CurrentNameLang }}</h5>
                                    {{-- <h5>{{ $product->product->quantity }}</h5> --}}
                                    <h2>{{ $product->product->CurrentNameLang }}</h2>
                                    <p>{{  $product->product->discount_price ??  $product->product->price}}</p>
                                    {{-- <p>Price: {{ $product->discounted_price ?? $product->product->price }}</p> --}}
                                </div>

                                <div>
                                    <div class="counter" data-product-id="{{ $product->id }}"
                                         data-max-quantity="{{ $product->product->quantity }}">
                                        <button class="p-qty-btn-minus decrease">-</button>

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
                                                readonly class="quantity-input" id="quantityInput_{{ $product->id }}"
                                                value="{{ $product->quantity }}">

                                        {{-- <span class=" quantity-input count" id="quantityInput_{{ $product->id }}" > {{ $product->quantity }} </span> --}}

                                        <button class="p-qty-btn-plus increase">+</button>

                                        {{-- <a class="remove-item" data-id="{{ $product->id }}" href="javascript:void(0)"><i
                                                class="fa fa-times-circle"></i></a> --}}
                                        <a class="remove-item" data-id="{{ $product->id }}" href="javascript:void(0)">
                                            <button class="delete"><i class="fa fa-trash-alt"> </i></button>

                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-warning">
                                <h4>
                                    {{__('general.PRODUCTS_NOT_EXIST')}}
                                </h4>
                            </div>
                        @endforelse
                    </div>
                    @if ($cart->total())
                        <div class="continue">
                            <a class="done" href="{{ route('order.index') }}">
                                <button>{{ __('cart.CONTINUE_AND_PAY') }}</button>
                            </a>
                        </div>
                    @endif
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    @if ($cart->total())
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            {{-- <div class="coupons">
                                <h2>{{ __('cart.COUPONS') }} </h2>
                                <p>{{ __('cart.ENTER_DISCOUNT_CODE') }}</p>

                                <x-front.alert type="success" />
                                <x-front.alert type="danger" />
                                <form id="nestedForm" action="{{ route('check_discount_code') }}" method="post">
                                    @csrf
                                    <input class="" name="discount_code" type="text"
                                        placeholder="{{ __('checkout.PLEASE_ENTER_DISCOUNT_CODE') }}">
                                    @error('discount_code')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                    <button type="submit">{{__('cart.ADD_COUPON')}}</button>
                                </form>
                            </div> --}}
                            <div class="all-details">
                                <h4>{{ __('cart.ORDER_SUMMARY') }}</h4>
                                <p>{{ __('cart.PRODUCTS') }} <span
                                            id="totalProducts">{{ $cart->get()->sum('quantity') }}</span></p>
                                {{-- <p>{{__('cart.DISCOUNT_CODE')}}<span> 200 رس</span></p> --}}
                                <hr>
                                <h2> {{ __('cart.TOTAL') }} <span id="totalPrice"
                                                                  class="total-price">{{ resolve('App\currency\Currency')->getCurrency($cart->total()) }}</span>
                                </h2>

                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        const csrf_token = "{{ csrf_token() }}";
        (function ($) {
            $('.remove-item').on('click', function (e) { // onclick
                var itemId = $(this).data('id'); // Get the ID of the item to be removed

                // AJAX request to delete the item from the cart
                $.ajax({
                    url: "/cart/" + itemId,
                    method: 'DELETE',
                    data: {
                        _token: csrf_token
                    },
                    success: function (response) {
                        // Remove the item from the cart UI
                        $(`#${itemId}`).remove();

                        fetchTotalPrice();
                        updateCartCount();

                        location.reload();


                    },
                    error: function (xhr, status, error) {
                        // Handle errors here
                        console.error(xhr.responseText);
                    }
                });
            });


            // Function to update cart count
            function updateCartCount() {
                $.ajax({
                    url: "{{ route('cart.count') }}",
                    type: 'GET',
                    success: function (response) {
                        if (response.count > 0) {
                            $('#cartCount').text(response.count);
                        } else {
                            $('#cartCount').text('');
                        }
                    }
                });
            }
        })(jQuery);
        (function ($) {
            $('.p-qty-btn-minus').on('click', function (e) {
                e.preventDefault();
                const productId = $(this).closest('.counter').data('product-id');
                const quantityInput = $(this).siblings('.quantity-input');
                let newQuantity = parseInt(quantityInput.val()) - 1;
                if (newQuantity < 1) return;
                updateQuantity(productId, newQuantity);
            });

            $('.p-qty-btn-plus').on('click', function (e) {
                e.preventDefault();
                const productId = $(this).closest('.counter').data('product-id');
                const quantityInput = $(this).siblings('.quantity-input');
                const maxQuantity = $(this).closest('.counter').data('max-quantity');
                let currentQuantity = parseInt(quantityInput.val());

                if (currentQuantity < maxQuantity) {
                    let newQuantity = currentQuantity + 1;
                    quantityInput.val(newQuantity);
                    updateQuantity(productId, newQuantity);
                } else {
                    alert('تم اضافة كل الكمية المتاحة');
                }
            });

            function updateQuantity(productId, newQuantity) {
                $.ajax({
                    url: `/cart/${productId}`,
                    method: 'PUT',
                    data: {
                        _token: csrf_token,
                        quantity: newQuantity
                    },
                    success: response => {
                        console.log(response);
                        // Update the quantity input value
                        $(`[data-product-id="${productId}"] .quantity-input`).val(newQuantity);
                        fetchTotalPrice();
                        updateTotalPrice();
                    }
                });
            }

            function updateTotalPrice() {
                $.ajax({
                    url: '/cart/total',
                    method: 'GET',
                    success: response => {
                        // Update the total price element with the new total
                        $('#totalPrice').html(response.totalPrice);
                    }
                });
            }

        })(jQuery);
    </script>
    <script>
        $(document).ready(function () {
            $('.p-qty-btn-minus').on('click', function () {
                var productId = $(this).closest('.counter').data('product-id');
                var quantityInput = $('#quantityInput_' + productId);
                var newQuantity = parseInt(quantityInput.val()) - 1;
                if (newQuantity >= 0) {
                    quantityInput.val(newQuantity);
                    updateTotalProducts();
                }
            });

            $('.p-qty-btn-plus').on('click', function () {
                // var productId = $(this).closest('.counter').data('product-id');
                // var quantityInput = $('#quantityInput_' + productId);
                // var newQuantity = parseInt(quantityInput.val()) + 1;
                // quantityInput.val(newQuantity);
                updateTotalProducts();
            });

            function updateTotalProducts() {
                var totalProducts = 0;
                $('.quantity-input').each(function () {
                    totalProducts += parseInt($(this).val());
                });
                $('#totalProducts').text(totalProducts);
            }
        });

        function fetchTotalPrice() {
            $.ajax({
                type: 'GET',
                url: '/cart/total',
                success: function (response) {
                    // Update the total price on the page
                    $('.total-price').text(response.totalPrice);
                },
                error: function (xhr, status, error) {
                    // Handle errors here (e.g., display an error message)
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
@endsection
