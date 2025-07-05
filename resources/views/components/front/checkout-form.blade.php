<label>الاسم الأول</label>
<input class="checkout-input" {{old('addr.billing.first_name')}} type="text" name="addr[billing][first_name]"
       placeholder="الاسم الاول">
@error('addr.billing.first_name')
<span class="error" style="color: red">{{ $message }}</span>
@enderror

<label>اسم العائلة </label>
<input class="checkout-input" {{old('addr.billing.last_name')}} type="text" name="addr[billing][last_name]"
       placeholder="اسم العائلة">
@error('addr.billing.last_name')
<span class="error" style="color: red">{{ $message }}</span>
@enderror

<label>رقم الجوال </label>
<input class="checkout-input" type="number" name="addr[billing][phone_number]"
       {{old('addr.billing.phone_number')}}
       placeholder="رقم الجوال">
@error('addr.billing.phone_number')
<span class="error" style="color: red">{{ $message }}</span>
@enderror

<label>الدولة</label>
<select class="select-country" name="addr[billing][country_id]" id="countries">
    <option value="" hidden>اختر الدولة</option>
    @forelse($countries as $country)
        <option value="{{$country->id}}">{{$country->name_ar}}</option>
    @empty
    @endforelse
</select>
@error('addr.billing.country_id')
<span class="error" style="color: red">{{ $message }}</span>
@enderror

<label>المدينة</label>
<select class="select-country" name="addr[billing][city_id]" id="cities">
    <option hidden>اختر المدينة</option>
    @forelse($cities as $city)
        <option value="{{$city->id}}">{{$city->name_ar}}</option>
    @empty
    @endforelse
</select>
@error('addr.billing.city_id')
<span class="error" style="color: red">{{ $message }}</span>
@enderror

<label>العنوان</label>
<input class="checkout-input" type="text" name="addr[billing][address]" placeholder="ادخل العنوان"
       value="{{old('addr.billing.address')}}">
@error('address')
<span class="error" style="color: red">{{ $message }}</span>
@enderror
