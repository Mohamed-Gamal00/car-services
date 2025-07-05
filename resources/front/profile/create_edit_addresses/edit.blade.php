@extends('front.profile.index')

@section('page_title', 'تعديل العنوان')
@push('styles')
<link rel="stylesheet" href="{{ asset('front/css/old-style.css') }}">
@endpush


@section('user_section')
    <div class="myAccount-page">
        <div id="acc-5" class="tab-pane fade in">
            <h3>{{__('profile.YOUR_ACCOUNT')}}</h3>
            <h5>{{__('profile.EDIT_NEW_ADDRESS')}}</h5>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="info">
                    <form action="{{route('update.address', $address->id)}}" method="post">
                        @csrf
                        @method('put')
                        <x-front.input name="{{__('profile.ADDRESS_NAME')}}" type="text" column="address_title"
                                       :value="$address->address_title"/>
                        <x-front.input name="{{__('profile.RECIPIENT_FIRST_NAME')}}" type="text" column="first_name"
                                       :value="$address->first_name"/>
                        <x-front.input name="{{__('profile.RECIPIENT_LAST_NAME')}}" type="text" column="family_name"
                                       :value="$address->family_name"/>
                        <x-front.input name="{{__('profile.PHONE')}}" type="number" column="phone_number"
                                       :value="$address->phone_number"/>
                        <x-front.input name="{{__('profile.ADDRESS')}}" type="text" column="address" :value="$address->address"/>

                        <input type="hidden" name="user_id" value="{{Auth::guard('web')->user()->id}}">
                        <input hidden name="country_id" value="{{$address->country_id}}">


                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <label>{{__('profile.CITY')}}</label>
                            <select class="select-country" name="city_id" id="cities">
                                <option disabled hidden>{{__('profile.SELECT_CITY')}}</option>
                                @forelse($cities as $city)
                                    <option
                                        value="{{$city->id}}" @selected($city->id == $address->city_id)>{{$city->name_ar}}</option>
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
