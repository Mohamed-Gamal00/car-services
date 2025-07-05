@extends('front.index')

@section('page_title', "انشاء حساب جديد")

<link rel="stylesheet" href="{{ asset('front/css/old-style.css') }}">



@section('front-section')
    <div class="loginSection">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="road">
                        <ul>
                            <li><img src="../images/home.png" alt=""></li>
                            <li>{{__('index.MAIN')}}</li>
                            <li> | </li>
                            <li>{{__('forms.LOGIN')}}</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="content">
                        <h4>{{__('forms.CREATE_NEW_ACCOUNT')}}</h4>
                        <p>{{__('forms.FILL_DETAILS_CREATE_ACCOUNT')}}</p>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <label> {{__('forms.FIRST_NAME')}}</label>
                            <input type="text" name="first_name" placeholder="{{__('forms.ENTER_FIRST_NAME')}}"
                                value="{{ old('first_name') }}">
                            @error('first_name')
                                <span class="error" style="color: red">{{ $message }}</span>
                            @enderror

                            <label> {{__('forms.LAST_NAME')}}</label>
                            <input type="text" name="family_name" placeholder="{{__('forms.ENTER_LAST_NAME')}}"
                                value="{{ old('family_name') }}">

                            @error('family_name')
                                <span class="error" style="color: red">{{ $message }}</span>
                            @enderror

                            <label>{{__('forms.EMAIL')}}</label>
                            <input type="email" name="email" placeholder="{{__('forms.ENTER_EMAIL')}}"
                                value="{{ old('email') }}">
                            @error('email')
                                <span class="error" style="color: red">{{ $message }}</span>
                            @enderror


                            <label>{{__('forms.COUNTRY')}}</label>
                            <select class="select-country" name="country_id" id="countries">
                                <option value="" hidden>{{__('forms.SELECT_COUNTRY')}}</option>
                                @forelse($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name_ar }}</option>
                                @empty
                                @endforelse
                            </select>

                            <label style="float: none;">{{__('forms.PHONE_NUMBER')}}</label>
                            <div style="display: flex">

                                <input type="number" name="phone_number" placeholder="{{__('forms.ENTER_PHONE_NUMBER')}}"
                                    value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror

                                <select class="select-country" name="country_code" id="country_code" style="width: 100px">
                                    <option value="" hidden>+</option>
                                    @forelse($countries as $country)
                                        <option value="{{ $country->id }}" data-phone="{{ $country->phone_code }}">
                                            {{ $country->phone_code }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('country_code')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <label>{{__('forms.CITY')}}</label>
                            <select class="select-country" name="city_id" id="cities">
                                <option hidden>{{__('forms.SELECT_CITY')}}</option>
                                @forelse($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name_ar }}</option>
                                @empty
                                @endforelse
                            </select>

                            <label>{{__('forms.ADDRESS')}}</label>
                            <input type="text" name="address" placeholder="{{__('forms.ENTER_ADDRESS')}}" value="{{ old('address') }}">
                            @error('address')
                                <span class="error" style="color: red">{{ $message }}</span>
                            @enderror


                            <label>{{__('forms.PASSWORD')}}</label>
                            <input name="password" type="password" placeholder="{{__('forms.ENTER_PASSWORD')}}">
                            @error('password')
                                <span class="error" style="color: red">{{ $message }}</span>
                            @enderror

                            <label>{{__('forms.CONFIRM_PASSWORD')}}</label>
                            <input name="password_confirmation" type="password" placeholder="{{__('forms.ENTER_CONFIRM_PASSWORD')}}">
                            @error('password_confirmation')
                                <span class="error" style="color: red">{{ $message }}</span>
                            @enderror

                            <button type="submit">{{__('forms.CREATE_ACCOUNT')}}</button>
                            <span> {{__('forms.ALREADY_HAVE_ACCOUNT')}}<a href="{{ route('login') }}">{{__('forms.LOGIN')}}</a></span>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#cities').empty(); // Empty the cities dropdown initially
            $('#countries').on('change', function() {
                var countryId = $(this).val();
                if (countryId) {
                    $.ajax({
                        url: "/cities/" + countryId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#cities').empty();
                            $.each(data, function(key, value) {
                                $('#cities').append('<option value="' + value.id +
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

@endsection
