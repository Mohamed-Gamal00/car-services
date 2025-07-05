@extends('front.index')

@section('page_title', 'انهاء الطلب')
@push('styles')
    <link rel="stylesheet" href="{{ asset('front/css/old-style.css') }}">
@endpush


@section('front-section')
    <x-front.alert type="success"/>
    <x-front.alert type="danger"/>
    <div class="cart-section">
        <div class="container">
            <div class="row">

                {{-- checkout 2 --}}
                <form action="{{ route('checkout') }}" method="post">
                    @csrf

                    <input type="hidden" id="shippingPriceInput" name="shipping_price">


                    {{-- check login and addresses --}}
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            {{-- تسجيل الدخول لو مش مسجل --}}
                            @if (!Auth::guard('web')->check())
                                {{-- if user not authentcated لو مش مسجل بعطيله خيارين --}}
                                <div class="must-login">
                                    <h3>{{ __('checkout.LOGIN_OR_CHECKOUT_AS_GUEST') }}</h3>
                                    <hr>
                                    {{--  سجل دخول ب اميل وباسوورد --}}
                                    <label class="radioButton login">{{ __('checkout.LOGIN') }}
                                        <input type="radio" name="radio" id="visitorRadio">
                                        <span class="checkmark"></span>
                                    </label>

                                    {{-- او سجل ك ضيف --}}
                                    <label class="radioButton">{{ __('checkout.GUEST') }}
                                        <input type="radio" checked name="radio" id="loginRadio">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            @else
                                {{-- لو اليوزر مسجل هظهرله العناوين ويختار العنوان اللي هيوصله عليه الالطلب --}}
                                <div class="must-login">
                                    <h3>{{ __('checkout.CHOOSE_SAVED_ADDRESS') }}</h3>
                                    <hr>
                                    @forelse($user->addresses as $useAddress)
                                        <label style="width: 100%;" class="radioButton">{{ $useAddress->address_title }}
                                            <input type="radio" name="user_address" value="{{ $useAddress->id }}"
                                                   data-city-id="{{ $useAddress->city_id }}">
                                            <span class="checkmark"></span>
                                        </label>
                                    @empty
                                    @endforelse
                                </div>

                            @endif
                        </div>

                        {{-- if user is authenticated show thid form so he can add another address if he want --}}
                        @if (Auth::guard('web')->check())
                            {{-- if user exist --}}
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="details">
                                    <label class="radioButton" style="width: 100%;">
                                        <h3>{{ __('checkout.ADD_ADDRESS') }}</h3>
                                        <input type="radio" name="user_address" id="add_new_address_radio"
                                               value="add_address"> <!-- Add value attribute -->
                                        <span class="checkmark"></span>
                                        @error('user_address')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <hr>
                                    {{-- حقول العنوان اللي هيتسجل عشان يتم عملية الدفع --}}
                                    {{-- لو اليوزر مسجل وهيضيف عنوان جديد --}}
                                    <div id="billing_address_fields" style="display: none;">
                                        {{--الاسم الاول--}}
                                        <div>
                                            <label>{{ __('checkout.FIRST_NAME') }}</label>
                                            <input class="checkout-input" value="{{ old('addr.shipping.first_name') }}"
                                                   type="text" name="addr[shipping][first_name]"
                                                   placeholder="{{ __('checkout.FIRST_NAME') }}">
                                            @error('addr.shipping.first_name')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror

                                        </div>

                                        {{--اسم العائلة--}}
                                        <div>
                                            <label>{{ __('checkout.LAST_NAME') }}</label>
                                            <input class="checkout-input" value="{{ old('addr.shipping.last_name') }}"
                                                   type="text" name="addr[shipping][last_name]"
                                                   placeholder="{{ __('checkout.LAST_NAME') }}">
                                            @error('addr.shipping.last_name')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror

                                        </div>

                                        {{--الجوال و كود الدولة--}}
                                        <div>
                                            <label>{{ __('checkout.MOBILE_NUMBER') }}</label>
                                            <div style="display: flex;">
                                                <input style="width: 75%;" class="checkout-input" type="number"
                                                       name="addr[shipping][phone_number]"
                                                       placeholder="{{ __('checkout.MOBILE_NUMBER') }}"
                                                       value="{{ old('addr.shipping.phone_number') }}">

                                                <select style="width: 25%;" class="checkout-input" name="country_code"
                                                        value="{{ old('country_code') }}">
                                                    <option value="+" selected disabled></option>
                                                    @forelse($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->phone_code }}
                                                        </option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            @error('country_code')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror
                                            @error('addr.shipping.phone_number')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{--الدولة--}}
                                        <div>
                                            <label>{{ __('checkout.COUNTRY') }}</label>
                                            <select class="select-country" name="addr[shipping][country_id]"
                                                    id="countries">
                                                <option value="" hidden>{{ __('checkout.SELECT_COUNTRY') }}</option>
                                                @forelse($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name_ar }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('addr.shipping.country_id')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror

                                        </div>

                                        {{--المدينة--}}
                                        <div>
                                            <label>{{ __('checkout.CITY') }}</label>
                                            <select class="select-country" name="addr[shipping][city_id]"
                                                    id="citySelect">
                                                <option hidden>{{ __('checkout.SELECT_CITY') }}</option>
                                                @forelse($cities as $city)
                                                    <option value="{{ $city->id }}">{{ $city->name_ar }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('addr.shipping.city_id')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror

                                        </div>

                                        {{--العنوان--}}
                                        <div>
                                            <label>{{ __('checkout.ADDRESS') }}</label>
                                            <input class="checkout-input" type="text" name="addr[shipping][address]"
                                                   placeholder="ادخل العنوان"
                                                   value="{{ old('addr.shipping.address') }}">
                                            @error('addr.shipping.address')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror

                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- if user not auth --}}
                        @else
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="details" id="billingAddressSection">
                                    {{-- <h3>{{ __('checkout.ADD_ADDRESS') }}</h3> --}}
                                    <label class="radioButton" style="width: 100%;">
                                        <h3>{{ __('checkout.ADD_ADDRESS') }}</h3>
                                        <input type="radio" name="radio" id="add_new_address_radio"
                                               value="add_address"> <!-- Add value attribute -->
                                        <span class="checkmark"></span>
                                        @error('user_address')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <hr>
                                    {{-- hide billing fields when user be not authenticated --}}
                                    {{-- لو اليوزر مش مسجل وهيضيف عنوان جديد --}}
                                    <div id="billing_address_fields" style="display: none;">
                                        <div>
                                            <label>{{ __('checkout.FIRST_NAME') }}</label>
                                            <input class="checkout-input" value="{{ old('addr.billing.first_name') }}"
                                                   type="text" name="addr[billing][first_name]"
                                                   placeholder="الاسم الاول">
                                            @error('addr.billing.first_name')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label>{{ __('checkout.LAST_NAME') }}</label>
                                            <input class="checkout-input" value="{{ old('addr.billing.last_name') }}"
                                                   type="text" name="addr[billing][last_name]"
                                                   placeholder="اسم العائلة">
                                            @error('addr.billing.last_name')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label>{{ __('checkout.EMAIL') }}</label>
                                            <input class="checkout-input" value="{{ old('guest_email') }}"
                                                   type="email" name="guest_email" placeholder="البريد الالكتروني">
                                            @error('guest_email')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div>
                                            <label>{{ __('checkout.MOBILE_NUMBER') }}</label>
                                            <div style="display: flex;">
                                                <input style="width: 75%;" class="checkout-input" type="number"
                                                       name="addr[billing][phone_number]" placeholder="رقم الجوال"
                                                       value="{{ old('addr.billing.phone_number') }}">


                                                <select style="width: 25%;" class="checkout-input" name="country_code"
                                                        value="{{ old('country_code') }}">
                                                    <option value="+" selected disabled></option>
                                                    @forelse($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->phone_code }}
                                                        </option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            @error('country_code')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror
                                            @error('addr.billing.phone_number')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div>
                                            <label>{{ __('checkout.COUNTRY') }}</label>
                                            <select class="select-country" name="addr[billing][country_id]"
                                                    id="countries">
                                                <option value="" hidden>{{ __('checkout.SELECT_COUNTRY') }}</option>
                                                @forelse($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name_ar }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('addr.billing.country_id')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label>{{ __('checkout.CITY') }}</label>
                                            <select id="citySelect" class="select-country"
                                                    name="addr[billing][city_id]">
                                                @forelse($cities as $city)
                                                    <option value="{{ $city->id }}">{{ $city->name_ar }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('addr.billing.city_id')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror

                                        </div>

                                        <div>
                                            <label>{{ __('checkout.ADDRESS') }}</label>
                                            <input class="checkout-input" type="text" name="addr[billing][address]"
                                                   placeholder="ادخل العنوان" value="{{ old('addr.billing.address') }}">
                                            @error('addr.billing.address')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror

                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- check paymen , shipping , cart items --}}
                    <div class="col-md-8 col-sm-12 col-xs-12">
                        {{-- طريقة الشحن --}}
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="how-shipping">
                                <h3>{{ __('checkout.SHIPPING_METHOD') }}</h3>
                                <hr>
                                {{-- الاستلام من المتجر --}}
                                @if ($shipping->add_pickup_from_store)
                                    <label class="radioButton">{{ __('checkout.STORE_PICKUP') }}
                                        <input checked type="radio" value="noShipping" name="shipping">
                                        <span class="checkmark" name="shipping"></span>
                                    </label>
                                @endif
                                {{-- في حالة الشحن بناء علي المنطقة --}}
                                @if ($shipping->add_price_based_on_city)
                                    <label id="shippingCostContainer" class="radioButton">
                                        {{ __('checkout.SHIPPING_COST_BASED_ON_REGION') }} - <span
                                                id="shippingCost">...</span>
                                        {{-- <span>رس</span> --}}
                                        <input type="radio" name="shipping">
                                        <span class="checkmark"></span>
                                    </label>
                                @endif
                                {{-- في حالة الشحن بناء علي التكلفة الثابته للشحن --}}
                                @if ($shipping->add_normal_price)
                                    <label id="shippingCostContainer" class="radioButton">
                                        {{ __('checkout.FLAT_SHIPPING_COST') }}
                                        <span>{{ resolve('App\currency\Currency')->getCurrency($shipping->normal_shipping_price) }}</span>
                                        <input type="radio" name="shipping">
                                        <span class="checkmark"></span>
                                    </label>

                                    <input hidden name="shipping_price"
                                           value="{{ resolve('App\currency\Currency')->getCurrency($shipping->normal_shipping_price) }}">
                                @endif

                                {{-- في حالة الشحن بناء علي الوزن --}}
                                @if ($shipping->add_wight_price)
                                    <label id="shippingCostContainer" class="radioButton">
                                        {{ __('checkout.COST_WEIGHT_PRICE') }}
                                        {{-- <span>{{ resolve('App\currency\Currency')->getCurrency($shipping->weight_price) }}</span> --}}
                                        <span id="shippingCost">...</span>
                                        <input type="radio" name="shipping">
                                        <span class="checkmark"></span>
                                        {{-- <input hidden name="shipping_price"
                                        value="{{ resolve('App\currency\Currency')->getCurrency($shipping->weight_price) }}"> --}}
                                    </label>
                                @endif
                            </div>
                        </div>
                        {{-- طريقة الدفع او السداد  --}}
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="how-payment">
                                <h3>{{ __('checkout.PAYMENT_METHOD') }}</h3>
                                <hr>
                                <label class="radioButton"> {{ __('checkout.CASH_ON_DELIVERY') }}
                                    <input type="radio" name="payment_method" value="cash_on_delivery" checked>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="radioButton"> بطاقة دفع
                                    <input type="radio" name="payment_method" value="card_payment">
                                    <span class="checkmark"></span>
                                </label>

                            </div>
                        </div>
                        {{-- سلة المشتريات --}}
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="shoppingBasket">
                                <a href="{{ route('cart.index') }}">
                                    <h3 style="color: #2c368e">{{ __('checkout.SHOPPING_CART') }} المشتريات</h3>
                                </a>
                                <hr>
                                @forelse($cart->get() as $products)
                                    <div class="col-md-12 col-sm-12 col-xs-12" id="{{ $products->id }}">
                                        <div class="product">
                                            <div class="col-md-2 col-sm-2 col-xs-12">
                                                <div class="pic">
                                                    <img src="{{ $products->product->image_url }}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="content">
                                                    <h4>{{ $products->product->name }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-6">
                                                <div class="counter" data-product-id="{{ $products->id }}">
                                                    <input readonly class="quantity-input"
                                                           value="{{ $products->quantity }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="total">
                                                    <h2>{{ resolve('App\currency\Currency')->getCurrency($products->quantity *$products->product->discount_price ?? $products->product->price) }}
                                                    </h2>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                <hr>
                                <h4>{{ __('checkout.TOTAL_PRODUCTS') }}<span>{{ $cart->get()->sum('quantity') }}</span>
                                </h4>

                                <div class="totalPrice">
                                    <h3>{{ __('checkout.TOTAL') }}
                                        @if (!is_null($discountPrice) && $cart->total() > $discountPrice)
                                            {{-- <span>{{ resolve('App\currency\Currency')->getCurrency($cart->total() - $discountPrice) }}</span> --}}
                                            <span>{{ resolve('App\currency\Currency')->getCurrency($cart->total()) }}</span>
                                        @else
                                            <span>{{ resolve('App\currency\Currency')->getCurrency($cart->total()) }}</span>
                                        @endif
                                    </h3>
                                </div>
                                <div class="totalPrice">
                                    <h3>الاجمالي بعد الشحن
                                        <span id="totalPrice">...</span>
                                    </h3>
                                </div>


                            </div>
                        </div>
                        {{-- كود الخصم --}}
                        <div id="gap_form"><input type="hidden" name="PostVar"/><a id="myLink"
                                                                                   href="javascript:Form2.submit()"></a>
                        </div>

                        {{-- تأكيد --}}
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="confirm">
                                <h3>{{ __('checkout.CONFIRM') }} </h3>
                                <hr>
                                <label>{{ __('checkout.NOTE') }}</label>
                                <textarea name="note"></textarea>
                                <label class="radioButton">{{ __('checkout.SUBSCRIBE_TO_MAILING_LIST') }}
                                    <input type="checkbox" name="join_news">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="radioButton">{{ __('checkout.I_HAVE_READ_AND_AGREE_TO') }}<a
                                            href="{{ route('aboutus') }}">ا{{ __('checkout.PRIVACY_POLICY') }}</a>
                                    <input type="checkbox" name="terms">
                                    <span class="checkmark"></span>
                                </label>
                                @error('terms')
                                <span class="error" style="color: red">{{ $message }}</span>
                                @enderror


                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="done">
                                <button
                                        onclick="$(this).closest('form').submit()">{{ __('checkout.COMPLETE_ORDER') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--=====================================================================-->
    {{-- login popup 3 --}}
    <div class="mustLoginFirst" id="LoginFirst">
        <div class="con">
            <h3>{{ __('checkout.YOU_MUST_LOG_IN') }}</h3>
            <span class="close" id="hideLoginFirst"><i class="fa fa-times-circle"></i></span>
            <form action="{{ route('checkout.login') }}" method="post">
                @csrf
                <label>{{ __('checkout.EMAIL') }}</label>
                <input type="text" name="email" placeholder="ادخل البريد الالكتروني">
                @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <label>{{ __('checkout.PASSWORD') }}</label>
                <input type="password" name="password" placeholder="ادخل كلمة المرور">

                <button type="submit">{{ __('checkout.LOGIN') }}</button>
            </form>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            (function () {
                $('#gap_form').wrap(
                    `                             <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="discountCode">
                        <h3>{{ __('checkout.DISCOUNT_COUPON') }}</h3>
                        <hr>

                        <form id="nestedForm" action="{{ route('check_discount_code') }}" method="post">
                            @csrf
                    <label>{{ __('checkout.PLEASE_ENTER_DISCOUNT_CODE') }}</label>
                            <input class="copone-input" name="discount_code" type="text"
                                placeholder="{{ __('checkout.PLEASE_ENTER_DISCOUNT_CODE') }}">
                            @error('discount_code')
                    <span class="error" style="color: red">{{ $message }}</span>
                            @enderror
                    <button type="submit">{{ __('checkout.ADD') }}</button>
                        </form>
                    </div>
                </div>`
                );
            })();
        });
    </script>

    {{-- غالبا مش مستخدمة في الصفحة دي --}}
    <script>
        const csrf_token = "{{ csrf_token() }}";
        (function ($) {
            $('.remove-item').on('click', function (e) { // onclick
                //ajax setting
                $.ajax({
                    url: "/cart/" + $(this).data('id'), // will get attribute data-id value
                    method: 'delete',
                    data: {
                        _token: csrf_token // variable
                    },
                    success: Response => {
                        $(`#${$(this).data('id')}`).remove(); // this will remove the all element
                    }
                });
            });

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
                let newQuantity = parseInt(quantityInput.val()) + 1;
                updateQuantity(productId, newQuantity);
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
                    }
                });
            }
        })(jQuery);

        $(document).ready(function () {
            $('#citySelect').empty(); // Empty the cities dropdown initially
            $('#countries').on('change', function () {
                var countryId = $(this).val();
                if (countryId) {
                    $.ajax({
                        url: "/cities/" + countryId,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#citySelect').empty();
                            $.each(data, function (key, value) {
                                $('#citySelect').append('<option value="' + value.id +
                                    '">' + value.name_ar + '</option>');
                            });
                        }
                    });
                } else {
                    $('#cities').empty(); // This line will empty the cities dropdown
                }
            });
        });
    </script>
    {{-- add new address --}}
    <script>
        // Get the radio button and billing address fields
        const addNewAddressRadio = document.getElementById('add_new_address_radio');
        const billingAddressFields = document.getElementById('billing_address_fields');

        // Add event listener to the radio button
        addNewAddressRadio.addEventListener('change', function () {
            // If the radio button is checked, show the billing address fields, otherwise hide them
            if (this.checked) {
                billingAddressFields.style.display = 'block';
            } else {
                billingAddressFields.style.display = 'none';
            }
        });
    </script>
    {{-- must login --}}
    <script>
        $(document).ready(function () {
            // Hide the billing address section initially
            $('#billingAddressSection').show();

            // Event listener for visitor radio button click
            $('#visitorRadio').click(function () {
                $('#billingAddressSection').hide().fadeIn(500);
                $('#LoginFirst').show() // Show the billing address section
            });

            // close the form login
            $('#hideLoginFirst').click(function () {
                $('#LoginFirst').hide();
            });

            // Event listener for login radio button click
            $('#loginRadio').click(function () {
                $('#billingAddressSection').show(); // Hide the billing address section
            });
        });
    </script>

    {{-- get shipping based on city --}}
    <script>
        document.getElementById('citySelect').addEventListener('change', function () {
            var cityId = this.value;
            var shippingCostElement = document.getElementById('shippingCost');
            var shippingPriceElement = document.getElementById('shippingPriceInput');
            /* new */
            var totalElement = document.getElementById('totalPrice'); // Element to display the total price


            // Send AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/get-shipping-cost/' + cityId);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Parse JSON response
                        var response = JSON.parse(xhr.responseText);
                        var formattedShippingCost = response.formatted_shipping_cost;

                        /* new */
                        // var shipping_cost = parseFloat(response.shipping_cost);
                        // var total_Cart = parseFloat(response.total_Cart);
                        // console.log(total_Cart + shipping_cost);
                        var total_Cart = parseFloat(response.total_Cart);
                        console.log(total_Cart);
                        totalElement.textContent = total_Cart.toFixed(2);
                        // Update shipping cost element with formatted shipping cost
                        shippingCostElement.textContent = formattedShippingCost;
                        shippingPriceElement.value = formattedShippingCost;
                    } else {
                        shippingCostElement.textContent = 'برجاء تحديد العنوان';
                    }
                }
            };
            xhr.send();
        });

        document.querySelectorAll('input[name="user_address"]').forEach(function (radioButton) {
            radioButton.addEventListener('change', function () {
                if (this.checked) {
                    var cityId = this.dataset
                        .cityId; // Assuming you have a data attribute 'data-city-id' in your radio buttons
                    var shippingCostElement = document.getElementById('shippingCost');
                    var shippingPriceElement = document.getElementById('shippingPriceInput');

                    var totalElement = document.getElementById(
                        'totalPrice'); // Element to display the total price

                    // Send AJAX request
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', '/get-shipping-cost/' + cityId);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                // Parse JSON response
                                var response = JSON.parse(xhr.responseText);
                                var formattedShippingCost = response.formatted_shipping_cost;
                                /* new */
                                // var shipping_cost = parseFloat(response.shipping_cost);
                                var total_Cart = parseFloat(response.total_Cart);
                                console.log(total_Cart);
                                totalElement.textContent = total_Cart.toFixed(2);
                                // Update shipping cost element with formatted shipping cost
                                shippingCostElement.textContent = formattedShippingCost;
                                shippingPriceElement.value = formattedShippingCost;
                            } else {
                                shippingCostElement.textContent = 'برجاء تحديد العنوان';
                            }
                        }
                    };
                    xhr.send();
                }
            });
        });
    </script>
@endsection

