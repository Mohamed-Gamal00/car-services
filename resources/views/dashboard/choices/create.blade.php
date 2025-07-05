@extends('dashboard.index')

@section('title', 'اضافة خدمة جديد')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('main_choices.index') }}">الخدمات الاضافية</a></li>
    <li class="breadcrumb-item active" aria-current="page">اضافة خدمة جديد</li>
@endsection


@section('section')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Nav tabs -->

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active p-3" id="home2" role="tabpanel">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        {{-- Form Start --}}
                                        <form method="post" action="{{ route('main_choices.store') }}"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="type" value="main">

                                            <div class="row mb-3">
                                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم
                                                    الخدمة</label>
                                                <div class="col-sm-10">
                                                    <x-form.input type="text" name="name" id="example-text-input"/>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم
                                                    الخدمة باللغة الانجليزية</label>
                                                <div class="col-sm-10">
                                                    <x-form.input type="text" name="name_en" id="example-text-input"/>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">سعر
                                                    الخدمة</label>
                                                <div class="col-sm-10">
                                                    <x-form.input type="text" name="service_price"
                                                                  id="example-text-input"/>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">الصورة
                                                    الرئيسية</label>
                                                <div class="col-sm-10">
                                                    <input type="file" class="form-control" name="image"
                                                           id="imageUpload"
                                                           data-buttonname="btn-secondary" accept="image/*">
                                                    @error('image')
                                                    <span class="error">{{ $message }}</span>
                                                    @enderror
                                                    <img src="#" id="imagePreview" class="img-thumbnail rounded mt-3"
                                                         alt="Preview"
                                                         style="width: 150px; display: none;">
                                                </div>
                                            </div>

                                            <div>
                                                <button class="btn btn-primary mt-5" type="submit">حفظ</button>
                                            </div>
                                        </form>
                                    </div><!-- end cardbody -->
                                </div><!-- end card -->
                            </div> <!-- end col -->
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>



    {{-- Form End --}}

@endsection
