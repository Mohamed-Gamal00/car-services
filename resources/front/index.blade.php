@php use Illuminate\Support\Facades\Auth; @endphp
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <x-front.website-title/>
    @php
        $settings = App\Models\Setting::first();
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ "storage/$settings->image " }}">

    <meta name="description" content="@yield('meta_description')"/>

    <link href="{{ asset('front/fontawesome/css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/search-bar.css') }}">
    @stack('styles')

    <!-- Moyasar Styles -->
    <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.14.0/moyasar.css"/>


</head>

<body>
<div class="the-after" onclick="closeNav()"></div>
<!--=====================================================================-->
@php
    $advertisement = App\Models\Advertisement::first();
    $storeFeatuers = App\Models\StoreFatuer::all();
@endphp
<div class="news-ticker">
    <div class="news-ticker-content">
        @for ($i = 0; $i < 5; $i++)
            <span><i class="fa fa-circle"></i>{!! translateWithHTMLTags($advertisement->title) !!}</span>
        @endfor
    </div>
</div>
@php
    $settings = App\Models\Setting::first();
@endphp
        <!--============================= NEW ========================================-->
<div class="mainHeader">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="socialMedia">
                    <ul>
                        <li><a href="{{ $settings->twitter }}"> <i class="fab fa-x"></i> </a></li>
                        <li><a href="{{ $settings->tiktok }}"> <i class="fab fa-tiktok"></i> </a></li>
                        <li><a href="{{ $settings->snap }}"> <i class="fab fa-snapchat-ghost"></i> </a></li>
                        <li><a href="{{ $settings->instagram }}"> <i class="fab fa-instagram"></i> </a></li>
                        <li><a href="{{ $settings->facebook }}"> <i class="fab fa-facebook-f"></i> </a></li>

                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                @php
                    $locale = app()->getLocale();
                    $isArabic = $locale === 'ar';
                @endphp

                <div class="language">
                    <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        @if ($isArabic)
                            <img width="20" src="{{ asset('front/images/ksa.png') }}" alt=""> اللغة
                            العربية
                        @else
                            <img width="20" src="{{ asset('assets/images/flags/us_flag.jpg') }}"
                                 alt=""> English
                        @endif
                        <img src="{{ asset('front/images/arrow-down.png') }}" alt="">
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('change.language', 'en') }}">English</a>
                        </li>
                        <li>
                            <a href="{{ route('change.language', 'ar') }}">العربية</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="logo">
                    <a href="{{ route('front.home') }}"><img class="img-responsive"
                                                             src="{{ asset('storage/' . $settings->logo) }}"></a>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 text-center col-xs-12">
                <x-front.search-bar/>

                {{-- <div class="searchFilter">
                <x-front.search-bar />
                <hr>
                <select>
                    <option value="" disabled selected hidden>{{ __('index.SELECT_FROM_LIST') }}</option>
                    <option>القائمة</option>
                    <option>القائمة</option>
                    <option>القائمة</option>
                    <option>القائمة</option>
                    </optgroup>
                </select>
                <img src="images/arrow-down.png" alt="">
                <button><i class="fa fa-search"></i></button>
            </div> --}}
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="MoreIcons">
                    <ul>
                        <li class="icon">
                            <x-front.cart-component/>
                            <a href="{{ route('cart.index') }}"><img class="img-responsive"
                                                                     src="{{ asset('front/images/shopBag.png') }}"
                                                                     alt=""></a>
                        </li>
                        {{-- <a href="{{ route('user.wishlist') }}"><img class="img-responsive"
                                    src="{{ asset('front/images/heart.png') }}" alt=""></a> --}}

                        <li class="icon">
                            @if (Auth::guard('web')->check())
                                <a href="{{ route('user.wishlist') }}"><img class="img-responsive"
                                                                            src="{{ asset('front/images/heart.png') }}"
                                                                            alt=""></a>
                            @else
                                <a href="{{ route('guest.wishlist') }}"><img class="img-responsive"
                                                                             src="{{ asset('front/images/heart.png') }}"
                                                                             alt=""></a>
                            @endauth
                            {{-- <a href="{{ route('user.wishlist') }}"><img class="img-responsive"
                                    src="{{ asset('front/images/heart.png') }}" alt=""></a> --}}
                        </li>
                        </li>
                        <li class="icon">
                            <a href="{{ route('user.profile') }}"><img class="img-responsive"
                                                                       src="{{ asset('front/images/circleUser.png') }}"
                                                                       alt=""></a>

                        </li>
                        <li class="hidden-xx" onclick="openNav()">
                            <i class="fa fa-bars"></i>
                        </li>
                        <li class="introUser">


                            @if (Auth::guard('web')->user())
                                <div class="content">
                                    <h5>{{ __('index.WELLCOME') }}</h5>
                                    <a
                                            href="{{ route('user.info') }}">{{ Auth::guard('web')->user()->first_name }}</a>
                                </div>
                            @else
                                <div class="content">
                                    <h5>{{ __('index.WELLCOME') }}</h5>
                                    <button class="dropdown-login" type="button" data-toggle="dropdown"
                                            aria-expanded="false"> {{ __('index.LOGIN') }}<img
                                                src="{{ asset('front/images/arrow-down.png') }}" alt="">
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('login') }}">{{ __('index.LOGIN') }}<i
                                                        class="fa fa-angle-left"></i> </a>
                                        </li>
                                        <li><a href="{{ route('register') }}">{{ __('index.CREATE_NEW_ACCOUNT') }}<i
                                                        class="fa fa-angle-left"></i>
                                            </a></li>
                                    </ul>
                                </div>
                            @endif
                        </li>

                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<!--============================== mainMenu ====================================-->
@php
    $categories = App\Models\MainCategory::has('products')->with('children')->whereNull('parent_id')->get();
@endphp
<div class="mainMenu">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ asset('front/images/bars.png') }}"
                                     alt="">{{ __('index.AVAILABLE_PRODUCTS_LIST') }}<i
                                        class="fa fa-angle-down"></i>
                            </a>
                            @once
                                @php
                                    function renderCategories($categories) {
                                        $html = '';
                                        foreach ($categories as $subcategory) {
                                            $html .= '<li class="dropdown-submenu">';
                                            $html .= '<a href="' . route('front.products') . '?category_id=' . $subcategory->id . '">' . $subcategory->name . '</a>';
                                            if ($subcategory->children->isNotEmpty()) {
                                                $html .= '<ul class="dropdown-menu">';
                                                $html .= renderCategories($subcategory->children);
                                                $html .= '</ul>';
                                            }
                                            $html .= '</li>';
                                        }
                                        return $html;
                                    }
                                @endphp

                            @endonce

                            <ul class="dropdown-menu multi-level">

                                @foreach ($categories as $category)
                                    <li class="dropdown-submenu">
                                        <a href="{{ route('front.products') }}?category_id={{ $category->id }}">
                                            {{ $category->CurrentNameLang }} <i class="fa fa-angle-left"></i>
                                        </a>
                                        @if ($category->children->isNotEmpty())
                                            <ul class="dropdown-menu">
                                                {!! renderCategories($category->children) !!}
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                        </li>
                        <li><a href="{{ route('offer_products') }}">{{ __('index.OFFERS') }}</a></li>
                        <li><a href="{{ route('front.bulk_orders') }}">{{ __('index.COMPANY_ORDERS') }}</a></li>
                        <li><a href="{{ route('front.all_brands') }}">{{ __('index.BRAND_ID') }}</a></li>
                        {{-- <li><a href="">{{ __('index.SHIPPING_OPTIONS') }}</a></li> --}}
                        <li><a href="{{ route('cart.index') }}"> {{ __('index.CART') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="links">
                    <a href="{{ route('front.bulk_orders') }}">{{ __('index.WHOLESALE_ORDERS') }}</a>
                    <a href="{{ route('representative_orders') }}">{{ __('index.REPRESENTATIVE_ORDERS') }}</a>

                </div>
            </div>
        </div><!-- Row -->
    </div><!-- Container -->
</div>


<!--================================= mySidenav ====================================-->
<div id="mySidenav" class="sidenav">
    <div class="col-sm-12 col-xs-12">
        <div class="logo">
            <a href="{{ route('front.home') }}"><img class="img-responsive"
                                                     src="{{ asset('storage/' . $settings->logo) }}"></a>
        </div>
    </div><!--col-sm-12-->
    <div class="col-sm-12 col-xs-12">
        <div class="menuNav">
            <ul>
                <li><a href="">{{ __('index.AVAILABLE_PRODUCTS_LIST') }}</a></li>
                <li><a href="{{ route('offer_products') }}">{{ __('index.OFFERS') }}</a></li>
                <li><a href="{{ route('front.bulk_orders') }}">{{ __('index.COMPANY_ORDERS') }}</a></li>
                <li><a href="{{ route('front.all_brands') }}">{{ __('index.BRAND_ID') }}</a></li>
                <li><a href="">{{ __('index.SHIPPING_OPTIONS') }}</a></li>
                <li><a href="{{ route('cart.index') }}"> {{ __('index.CART') }}</a></li>
            </ul>
        </div>
    </div><!--col-sm-12-->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="links">
            <a href="">{{ __('index.WHOLESALE_ORDERS') }}</a>
            <a href="">{{ __('index.REPRESENTATIVE_ORDERS') }}</a>

        </div>
    </div>

</div><!--sidenav-->

<!--sidenav-->
<!--=====================================================================-->
@yield('front-section')
<!--=====================================================================-->
<div class="subscribeSection">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="content">
                    <h4> {{ __('index.subscribeSection') }}</h4>
                    <h5>{!! translateWithHTMLTags($settings->subscription_title) !!}</h5>
                    <form action="{{ route('users_news') }}" method="post">
                        @csrf
                        <input name="email" type="email" placeholder="{{ __('index.EMAIL') }}">
                        <button type="submit"> {{ __('index.SUBSCRIPTION') }}</button>
                    </form>
                    @error('email')
                    <p class="error" style="color: red">{{ $message }}</p>
                    @enderror
                    <p>{{ __('index.UN_SUBSCRIPTION') }}</p>
                </div>
            </div>

        </div>
    </div>
</div>
<!--=====================================================================-->

<div class="footer">
    <div class="container-fluid">
        <div class="row">
            @forelse ($storeFeatuers as $featuer)
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="one">
                        <img src="{{ $featuer->image_url }}" alt="">
                        <h3>{{ $featuer->CurrentTitleLang }}</h3>
                        <p>{!! translateWithHTMLTags($featuer->description) !!}</p>
                    </div>
                </div>
            @empty
                <p></p>
            @endforelse
            <hr>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="footer-content">
                    <h4>{{ __('index.CONTACT_US') }}</h4>
                    <div class="data">
                        <div class="icon">
                            <img src="{{ asset('front/images/icon-call.png') }}" alt="">
                        </div>
                        <div class="content">
                            <h6>{{ __('index.CONTACT_NUMBER') }}</h6>
                            <p>{{ $settings->phone_number }}</p>
                        </div>
                    </div>
                    <div class="data">
                        <div class="icon">
                            <img src="{{ asset('front/images/icon-whats.png') }}" alt="">
                        </div>
                        <div class="content">
                            <h6>{{ __('index.WHATSAPP_CONTACT') }}</h6>
                            <p>{{ $settings->phone_number }}</p>
                        </div>
                    </div>
                    <div class="data">
                        <div class="icon">
                            <img src="{{ asset('front/images/icon-msg.png') }}" alt="">
                        </div>
                        <div class="content">
                            <h6>{{ __('index.EMAIL') }}</h6>
                            <p>{{ $settings->email }}</p>
                        </div>
                    </div>


                </div>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="footer-content">
                    <h4>{{ __('index.INFORMATION') }} </h4>
                    <ul>
                        <li><a href="{{ route('aboutus') }}">{{ __('index.ABOUT_US') }}</a></li>
                        <li><a href="{{ route('contact_us') }}">{{ __('index.CONTACT_US_INFO') }}</a></li>
                        <li><a href="{{ route('questions') }}">{{ __('index.FAQ') }}</a></li>
                        <li><a href="{{ route('privacy_policy') }}">{{ __('index.PRIVACY_POLICY') }}</a></li>
                        <li><a
                                    href="{{ route('representative_orders') }}">{{ __('index.INTERNAL_ORDERS') }}</a>
                        </li>
                    </ul>


                </div>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="footer-content">
                    <h4>{{ __('index.ACCOUNT') }} </h4>
                    <ul>
                        <li><a href="{{ route('login') }}"> {{ __('index.LOGIN') }}</a></li>
                        <li><a href="{{ route('register') }}">{{ __('index.CREATE_NEW_ACCOUNT') }}</a></li>
                        {{-- <li><a href="">{{ __('index.MY_ORDERS') }} </a></li> --}}
                        @if (Auth::guard('web')->check())
                            <li><a href="{{ route('user.main.orders') }}">{{ __('index.MY_ORDERS') }}</a></li>
                        @else
                            <li><a href="{{ route('guest.main.orders') }}">{{ __('index.MY_ORDERS') }}</a></li>
                        @endauth
                        @auth
                            <li><a href="{{ route('user.return_products') }}">{{ __('index.RETURNS') }}</a>
                            </li>
                        @else
                            <li><a href="{{ route('guest.return_products') }}">{{ __('index.RETURNS') }}</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="footer-content">
                    <h4>{{ __('index.IMPORTANT_LINKS') }}</h4>
                    <ul>
                        @auth
                            <li><a
                                        href="{{ route('user.return_products') }}">{{ __('index.EXCHANGE_RETURNS') }}</a>
                            </li>
                        @else
                            <li><a
                                        href="{{ route('guest.return_products') }}">{{ __('index.EXCHANGE_RETURNS') }}</a>
                            </li>
                        @endauth
                        <li><a href="{{ route('shipping_policy') }}">{{ __('index.SHIPPING_POLICY') }}</a></li>
                        <li><a href="{{ route('terms_conditions') }}">{{ __('index.TERMS_CONDITIONS') }}</a>
                        </li>
                    </ul>


                </div>
            </div>
            <hr>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="copyright">
                    <p> {{ __('index.ALL_RIGHTS_RESERVED') }}</p>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="visa">
                    <img src="{{ asset('front/images/visa.png') }}" alt="">
                </div>
            </div>


        </div>
    </div>
</div>

<!--=====================================================================-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('front/js/jquery-1.11.0.min.js') }}"></script>
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('front/js/jquery-search.js') }}"></script>
<script src="{{ asset('front/js/wow.min.js') }}"></script>
<script>
    new WOW().init();
</script>

<script src="{{ asset('front/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('front/js/script.js') }}"></script>

<script>
    $(document).ready(function () {
        $('.addToCart').on('click', function (e) {
            e.preventDefault(); // Prevent the default button click behavior

            var form = $(this).closest('form'); // Find the nearest form element

            $.ajax({
                type: 'POST',
                url: form.attr('action'), // Form action URL
                data: form.serialize(), // Form data
                success: function (response) {
                    // Handle the response here (e.g., display a success message)
                    $('#exampleModalCenter').modal('show');

                    fetchTotalPrice();
                    updateCartCount();
                },
                error: function (xhr, status, error) {
                    // Handle errors here (e.g., display an error message)
                    console.error(xhr.responseText);
                }
            });
        });

        function fetchTotalPrice() {
            $.ajax({
                type: 'GET',
                url: '/cart/total',
                success: function (response) {
                    // Update the total price on the page
                    $('.content .total-price').text(response.totalPrice);
                },
                error: function (xhr, status, error) {
                    // Handle errors here (e.g., display an error message)
                    console.error(xhr.responseText);
                }
            });
        }

        // Function to update cart count
        function updateCartCount() {
            $.ajax({
                url: "{{ route('cart.count') }}",
                type: 'GET',
                success: function (response) {
                    $('#cartCount').text(response.count > 0 ? response.count : '0');
                },
            });
        }
    });
</script>

<!-- Moyasar Scripts -->
<script src="https://cdnjs.cloudflare.com/polyfill/v3/polyfill.min.js?version=4.8.0&features=fetch"></script>
<script src="https://cdn.moyasar.com/mpf/1.14.0/moyasar.js"></script>

@stack('scripts')

</body>

</html>