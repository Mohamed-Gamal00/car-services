@extends('dashboard.index')

@section('title', 'اضافة باقة')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">الخدمات</a></li>
    <li class="breadcrumb-item">اضافة باقة</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="repeater" action="{{ route('packages.store') }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3 mt-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم الباقة</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="name" value="{{ old('name') }}"/>
                            </div>
                        </div>
                        <div class="row mb-3 mt-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم الباقة باللغة
                                الانجليزية</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="name_en" value="{{ old('name_en') }}"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">السعر</label>
                            <div class="col-sm-10">
                                <x-form.input type="number" name="price" value="{{ old('price') }}"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">وقت الخدمة</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" placeholder="HH:mm" name="duration"
                                              value="{{ old('duration') }}"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">عدد مرات
                                الغسيل</label>
                            <div class="col-sm-10">
                                <x-form.input type="number" name="wash_count" value="{{ old('wash_count') }}"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">صلاحية الباقة
                                بالايام</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" placeholder="" name="validity_in_days"
                                              value="{{ old('validity_in_days') }}"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">حالة الباقة</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="is_active" aria-label="Default select example">
                                    @error('is_active')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                    <option value="1">مفعلة</option>
                                    <option value="0" selected>غير مفعلة</option>
                                </select>
                                @error('status')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="example-number-input" class="col-sm-2 col-form-label fw-bold">الوصف</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="7"
                                          name="description">{{ old('description') }}</textarea>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">ايقونة
                                الباقة</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="icon" id="imageUpload"
                                       data-buttonname="btn-secondary" accept="image/*">
                                @error('icon')
                                <span class="error">{{ $message }}</span>
                                @enderror
                                <img src="#" id="imagePreview" class="img-thumbnail rounded my-2" alt="Preview"
                                     style="width: 150px; display: none;">
                            </div>
                        </div>


                        {{-- الصورة الخلفية  --}}
                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">الصورة
                                الرئيسية</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="image" id="bgimageUpload"
                                       data-buttonname="btn-secondary" accept="image/*">
                                @error('image')
                                <span class="error">{{ $message }}</span>
                                @enderror
                                <img src="#" id="bgimagePreview" class="img-thumbnail rounded my-2" alt="Preview"
                                     style="width: 150px; display: none;">
                            </div>
                        </div>

                        {{-- مميزات الباقة --}}
                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold"> مميزات للباقة
                                (اختياري)</label>
                            <div class="col-sm-10">
                                <div data-repeater-list="package_features">
                                    <div class="row" data-repeater-item>
                                        <div class="mb-3 col-lg-2">

                                            <label class="form-label fw-bold" for="name">الميزة</label>
                                            <x-form.input type="text" id="name"
                                                          name="feature" value="{{ old('feature') }}"/>
                                        </div>

                                        <div class="mb-3 col-lg-2">
                                            <label class="form-label fw-bold" for="name">الميزة
                                                بالانجليزي</label>
                                            <x-form.input type="text" id="name"
                                                          name="feature_en" value="{{ old('feature_en') }}"/>
                                        </div>

                                        <div class="col-lg-2 col-sm-4 align-self-center">
                                            <label class="form-label fw-bold"></label>
                                            <div class="d-grid">
                                                <input data-repeater-delete type="button"
                                                       class="btn btn-primary mb-2" value="مسح"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input data-repeater-create type="button" class="btn btn-success mt-2 mt-sm-0"
                                       value="اضافة المزيد"/>
                            </div>
                        </div>

                        <div>
                            <div>

                                <button class="btn btn-primary" type="submit">حفظ الباقة</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

  


    @push('scripts')
        <script src="{{ asset('assets/libs/jquery.repeater/jquery.repeater.min.js') }}"></script>

        <script src="{{ asset('assets/js/pages/form-repeater.int.js') }}"></script>
    @endpush

@endsection
