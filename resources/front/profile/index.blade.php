@php use Illuminate\Support\Facades\Auth; @endphp
@extends('front.index')

@section('page_title', 'الصفحه الشخصية')


@section('front-section')

    <div class="orders-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="road" style="margin-bottom: 30px">
                        <ul>
                            <li><img src="images/home.png" alt=""></li>
                            <li>{{__('general.MAIN')}}</li>
                            <li> | </li>
                            <li> @yield('breadcrumb') </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="sideMenu">
                        @if (Auth::guard('web')->check())
                            <h4>{{__('index.WELLCOME')}} {{ Str::before(Auth::guard('web')->user()->name, '') }}</h4>
                            <p>{{ Auth::guard('web')->user()->email }}</p>
                        @endif

                        <div class="the-tabs">

                            <ul>
                                @if (Auth::guard('web')->check())
                                    <x-front.side-bar name="{{__('profile.FAVORITES')}}" class="fa fa-heart" route="user.wishlist" />
                                @endif

                                @if (Auth::guard('web')->check())
                                    <x-front.side-bar name="{{__('profile.ORDERS')}}" class="fa fa-cubes" route="user.main.orders" />
                                @else
                                    <x-front.side-bar name="{{__('profile.ORDERS')}}" class="fa fa-cubes" route="guest.main.orders" />
                                @endif

                                @if (Auth::guard('web')->check())
                                    <x-front.side-bar name="{{__('profile.RETURNS')}}" class="fa fa-undo-alt" route="user.return_products" />
                                @else
                                    <x-front.side-bar name="{{__('profile.RETURNS')}}" class="fa fa-undo-alt" route="guest.return_products" />
                                @endif

                                @if (Auth::guard('web')->check())
                                    <x-front.side-bar name="{{__('profile.ADDRESSES')}}" class="fa fa-map-marker-alt" route="user.addresses" />
                                @endif

                                @if (Auth::guard('web')->check())
                                    <x-front.side-bar name="{{__('profile.YOUR_ACCOUNT')}}" class="fa fa-user" route="user.info" />
                                @endif

                                @if (Auth::guard('web')->check())
                                    <li>
                                        <form action="{{ route('logout') }}" method="post">
                                            @csrf
                                            <button style="border: none; background: none; padding: 0; margin: 0;"><a>
                                                    <i class="fa fa-sign-out-alt"></i> {{__('profile.LOGOUT')}}</a>
                                            </button>


                                        </form>
                                    </li>
                                @endif
                            </ul>

                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="tab-content">
                        @yield('user_section')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
