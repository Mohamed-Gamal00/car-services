@extends('dashboard.index')

@section('title', 'تعديل باقة')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">الباقات</a></li>
    <li class="breadcrumb-item">تعديل باقة</li>
@endsection

@section('section')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Form Start --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="repeater" action="{{ route('packages.update', $package->id) }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <div>
                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم
                                    الباقة</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="name" type="text" id="example-text-input"
                                           value="{{ $package->name }}">
                                    @error('name')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 mt-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم الباقة
                                    باللغة
                                    الانجليزية</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" name="name_en" value="{{ $package->name_en }}"/>
                                    @error('name_en')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="example-number-input" class="col-sm-2 col-form-label fw-bold">السعر
                                    الحالي</label>
                                <div class="col-sm-10">
                                    <x-form.input type="number" name="price" value="{{ $package->price}}"/>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">وقت
                                    الخدمة</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" placeholder="HH:mm" name="duration"
                                                  value="{{ $package->duration  }}"/>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">عدد مرات
                                    الغسيل</label>
                                <div class="col-sm-10">
                                    <x-form.input type="number" name="wash_count" value="{{ $package->wash_count}}"/>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">
                                    صلاحية الباقة
                                    بالايام</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" placeholder="" name="validity_in_days"
                                                  value="{{ $package->validity_days  }}"/>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="example-number-input" class="col-sm-2 col-form-label fw-bold">الوصف</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="7"
                                              name="description">{{ $package->description }}</textarea>
                                </div>
                            </div>


                            {{-- حالة النشاط --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label fw-bold">حالة الباقة</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="is_active" aria-label="Default select example">
                                        @error('status')
                                        <span class="error">{{ $message }}</span>
                                        @enderror
                                        <option value="" hidden disabled>اختر حالة الباقة</option>
                                        <option value="1" @selected($package->is_active == '1')>نشط</option>
                                        <option value="0" @selected($package->is_active == '0')>غير نشط
                                        </option>
                                    </select>
                                    @error('status')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <x-dashboard.image-preview image="{{ asset('storage/' . $package->icon ) }}" fileName="icon"
                                                       width="150"
                                                       heigh="150" title="ايقونة الباقة"/>

                            <x-dashboard.image-preview image="{{ asset('storage/' . $package->image) }}"
                                                       fileName="image" width="150"
                                                       heigh="150" title="الصورة الرئيسية"/>

                            {{-- مميزات --}}

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label fw-bold">مميزات للباقة (اختياري)</label>
                                <div class="col-sm-10">
                                    <div data-repeater-list="package_features">

                                        @if (empty($package->features->first()->feature))
                                            <div class="row" data-repeater-item>
                                                <div class="mb-3 col-lg-2">
                                                    <label class="form-label fw-bold" for="name">الاسم</label>
                                                    <input type="text" id="name" name="feature"
                                                           class="form-control" placeholder="اكتب اسم الميزه"/>
                                                    @error('feature')
                                                    <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-lg-2">
                                                    <label class="form-label fw-bold" for="feature_en">الاسم
                                                        بالانجليزي</label>
                                                    <input type="text" id="feature_en"
                                                           name="feature_en"
                                                           class="form-control" placeholder="الاسم بالانجليزي"/>
                                                    @error('feature_en')
                                                    <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- end col -->
                                                <input name="feature_id" hidden>

                                                <div class="col-lg-2 col-sm-4 align-self-center">
                                                    <label class="form-label fw-bold"></label>
                                                    <div class="d-grid">
                                                        <input data-repeater-delete type="button"
                                                               class="btn btn-primary mb-2" value="مسح"/>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif


                                        @foreach ($package->features as $feature)
                                            <div class="row" data-repeater-item>
                                                <div class="mb-3 col-lg-2">
                                                    <label class="form-label fw-bold" for="name">الاسم</label>
                                                    <input type="text" id="name" name="feature"
                                                           value="{{ $feature->feature }}"
                                                           class="form-control"
                                                           placeholder="اكتب اسم الميزه"/>
                                                    @error('feature')
                                                    <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-lg-2">
                                                    <label class="form-label fw-bold" for="feature_en">الاسم
                                                        بالانجليزي</label>
                                                    <input type="text" id="feature_en"
                                                           name="feature_en"
                                                           value="{{ $feature->feature_en }}"
                                                           class="form-control" placeholder="الاسم بالانجليزي"/>
                                                    @error('feature_en')
                                                    <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <input value="{{ $feature->id }}" name="feature_id" hidden>

                                                <input value="{{ $feature->id }}" name="feature_delete" hidden>


                                                <div class="col-lg-2 col-sm-4 align-self-center">
                                                    <label class="form-label fw-bold"></label>
                                                    <div class="d-grid">
                                                        <input data-repeater-delete type="button"
                                                               class="btn btn-primary mb-2" value="مسح"/>
                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach

                                    </div>
                                    <input data-repeater-create type="button"
                                           class="btn btn-success mt-2 mt-sm-0" value="اضافة المزيد"/>
                                </div>
                            </div>


                            <div>
                                <button id="submitBtn" class="btn btn-primary mb-5" type="submit">حفظ الباقة</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


@push('scripts')
    <script src="{{ asset('assets/libs/jquery.repeater/jquery.repeater.min.js') }}"></script>

    <script src="{{ asset('assets/js/pages/form-repeater.int.js') }}"></script>
@endpush
