@extends('dashboard.index')

@section('title', 'الاعدادات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">الاعدادات</li>
@endsection

@section('section')

    <form action="{{route('settings.update', $setting->id)}}" method="post"
          enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-12">
                <div class="card">
                    <x-alert type='success'/>

                    <x-alert type='dark'/>
                    <div class="card-body">
                        {{-- Form Start --}}

                        {{-- <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">نبذه عن
                                                                                                    الخدمات</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="title" :value="$setting->title"/>
                            </div>
                        </div> --}}

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">اسم التطبيق</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="website_name" :value="$setting->website_name"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">اسم التطبيق بالانجليزي</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="website_name_en" :value="$setting->website_name_en"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">عنوان
                                التطبيق</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="address" :value="$setting->address"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">عنوان التطبيق باللغة
                                الانجليزية</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="address_en" :value="$setting->address_en"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">وصف التطبيق</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="subscription_title"
                                              :value="$setting->subscription_title"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">وصف التطبيق باللغة الانجليزية</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="subscription_title_en"
                                              :value="$setting->subscription_title_en"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">البريد الالكتروني</label>
                            <div class="col-sm-10">
                                <x-form.input type="email" name="email" :value="$setting->email"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">رقم الجوال</label>
                            <div class="col-sm-10">
                                <x-form.input type="number" name="phone_number" :value="$setting->phone_number"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">واتساب</label>
                            <div class="col-sm-10">
                                <x-form.input type="number" name="whatsaap" :value="$setting->whatsaap"/>
                            </div>
                        </div>


                        {{--                        <div class="row mb-3">--}}
                        {{--                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">الرقم--}}
                        {{--                                الضريبي</label>--}}
                        {{--                            <div class="col-sm-10">--}}
                        {{--                                <x-form.input type="text" name="tax_number" :value="$setting->tax_number"/>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        {{--                        <div class="row mb-3">--}}
                        {{--                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">ضريبة القيمة--}}
                        {{--                                المضافة</label>--}}
                        {{--                            <div class="col-sm-10">--}}
                        {{--                                <x-form.input type="text" name="value_added_tax" :value="$setting->value_added_tax"/>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        {{--                        <div class="row mb-3">--}}
                        {{--                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">رابط--}}
                        {{--                                الفيسبوك</label>--}}
                        {{--                            <div class="col-sm-10">--}}
                        {{--                                <x-form.input type="text" name="facebook" :value="$setting->facebook"/>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        {{--                        <div class="row mb-3">--}}
                        {{--                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">رابط--}}
                        {{--                                سناب</label>--}}
                        {{--                            <div class="col-sm-10">--}}
                        {{--                                <x-form.input type="text" name="snap" :value="$setting->snap"/>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        {{--                        <div class="row mb-3">--}}
                        {{--                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">رابط--}}
                        {{--                                تويتر</label>--}}
                        {{--                            <div class="col-sm-10">--}}
                        {{--                                <x-form.input type="text" name="twitter" :value="$setting->twitter"/>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        {{--                        <div class="row mb-3">--}}
                        {{--                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">رابط--}}
                        {{--                                الانستجرام</label>--}}
                        {{--                            <div class="col-sm-10">--}}
                        {{--                                <x-form.input type="text" name="instagram" :value="$setting->instagram"/>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="row mb-3">--}}
                        {{--                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">رابط--}}
                        {{--                                تيك توك</label>--}}
                        {{--                            <div class="col-sm-10">--}}
                        {{--                                <x-form.input type="text" name="tiktok" :value="$setting->tiktok"/>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">
                                مفتاح بوابة الدفع</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="publishable_key" :value="$setting->publishable_key"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">
                                الرمز السري لبوابة الدفع</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="secret_key" :value="$setting->secret_key"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">
                                مفتاح بوابة الرسائل</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="sms_api_key" :value="$setting->sms_api_key"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">
                                اسم مستخدم بوابة الرسائل</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="sms_user_name" :value="$setting->sms_user_name"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">
                                اسم الراسل</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="sernder" :value="$setting->sernder"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">
                                وقت العمل</label>
                            <div class="col-sm-5" style="position: relative;!important;">
                                <x-form.input type="text" placeholder="من"
                                              name="working_strat_time"
                                              :value="$setting->working_strat_time"/>
                            </div>
                            <div class="col-sm-5" style="position: relative;!important;">
                                <x-form.input type="text" placeholder="الي"
                                              name="working_end_time"
                                              :value="$setting->working_end_time"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">
                                وقت الراحة</label>
                            <div class="col-sm-5" style="position: relative;!important;">
                                <x-form.input type="text" placeholder="من"
                                              name="start_rest_time"
                                              :value="$setting->start_rest_time"/>
                            </div>
                            <div class="col-sm-5" style="position: relative;!important;">
                                <x-form.input type="text" placeholder="الي"
                                              name="end_rest_time"
                                              :value="$setting->end_rest_time"/>
                            </div>
                        </div>

                        {{--                        <div class="row mb-3">--}}
                        {{--                            <label class="col-sm-2 col-form-label fw-bold">حالة الطلب الافتراضيه</label>--}}
                        {{--                            <div class="col-sm-10">--}}
                        {{--                                <select class="form-select" name="order_status"--}}
                        {{--                                        aria-label="Default select example">--}}
                        {{--                                    <option selected hidden>اختر الحالة الافتراضيه للطلب</option>--}}
                        {{--                                    @forelse($orderStatus as $status)--}}
                        {{--                                        <option--}}
                        {{--                                                value="{{$status->id}}" @selected($status->id == $currentOrderStatus->id)>{{$status->name}}</option>--}}
                        {{--                                    @empty--}}
                        {{--                                    @endforelse--}}
                        {{--                                </select>--}}
                        {{--                                @error('order_status')--}}
                        {{--                                <span class="error">{{ $message }}</span>--}}
                        {{--                                @enderror--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        {{--                        <div class="row mb-3">--}}
                        {{--                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">--}}
                        {{--                                وقت العمل</label>--}}
                        {{--                            <div class="col-sm-5" style="position: relative;!important;">--}}
                        {{--                                <x-form.input type="time" id="timepicker"--}}

                        {{--                                              name="working_strat_time"--}}
                        {{--                                              :value="$setting->working_strat_time"/>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-sm-5" style="position: relative;!important;">--}}
                        {{--                                <x-form.input type="time" id="timepicker2"--}}
                        {{--                                              name="working_end_time"--}}
                        {{--                                              :value="$setting->working_end_time"/>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <x-dashboard.image-preview image="{{ asset('storage/' . $setting->image) }}" fileName="image"--}}
                        {{--                                                   heigh="32" width="32" title="ايقونة تبويب التطبيق في المتصفح"/>--}}
                        <x-dashboard.image-preview image="{{asset('storage/' . $setting->logo)}}" fileName="logo"
                                                   heigh="200" width="200" title="اللوجو"/>

                        <div>
                            <button id="submitBtn" class="btn btn-primary mt-5 mb-2" type="submit">حفظ التعديلات
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        #timepicker:before {
            content: '  من';
            margin-right: .6em;
            position: absolute;
            right: 40px;
            color: #9d9d9d;
        }

        #timepicker2:before {
            content: '  الي';
            margin-right: .6em;
            position: absolute;
            right: 40px;
            color: #9d9d9d;
        }
    </style>
@endsection