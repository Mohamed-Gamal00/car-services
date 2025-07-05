@extends('front.profile.index')

@section('page_title', 'انشاء عنوان جديد')

<link rel="stylesheet" href="{{ asset('front/css/old-style.css') }}">

@section('user_section')
    <div class="myAccount-page">
        <div id="acc-5" class="tab-pane fade in">
            <h3>{{__('profile.YOUR_ACCOUNT')}}</h3>
            <h5>{{__('profile.ADD_NEW_ADDRESS')}}</h5>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="info">
                    <form action="{{route('store.address')}}" method="post">
                        @csrf
                        
                        <x-front.input name="{{__('profile.ADDRESS_NAME')}}" type="text" column="address_title"/>
                        <x-front.input name=" {{__('profile.RECIPIENT_FIRST_NAME')}}" type="text" column="first_name"/>
                        <x-front.input name="{{__('profile.RECIPIENT_LAST_NAME')}}" type="text" column="family_name"/>
                        <x-front.input name="{{__('profile.PHONE')}}" type="number" column="phone_number"/>
                        <x-front.input name="{{__('profile.ADDRESS')}}" type="text" column="address"/>

                        <input hidden name="country_id" value="{{$countryId->country_id}}">

                        <input type="hidden" name="user_id" value="{{Auth::guard('web')->user()->id}}">

                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <label>{{__('profile.CITY')}}</label>
                            <select class="select-country" name="city_id" id="cities">
                                <option disabled hidden>{{__('profile.SELECT_CITY')}}</option>
                                @forelse($cities as $city)
                                    <option value="{{$city->id}}">{{$city->name_ar}}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('city_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div style="width:max-content;">

                            <a href="{{route('create.address')}}">
                                <button class="add-address" type="submit">{{__('profile.SAVE_ADDRESS')}}</button>
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div><!--acc-3-->

@endsection
