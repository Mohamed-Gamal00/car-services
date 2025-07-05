@extends('dashboard.index')
@section('title', 'تعديل خدمة')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('main_choices.index') }}">الخدمات</a></li>
    <li class="breadcrumb-item active" aria-current="page">تعديل خدمة</li>
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
                                        <form method="post" action="{{ route('main_choices.update', $choice->id) }}"
                                              enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                                            <input type="hidden" name="type" value="main">

                                            <div class="row mb-3">
                                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم
                                                    الخدمة</label>
                                                <div class="col-sm-10">
                                                    <x-form.input type="text" value="{{ old('name', $choice->name) }}"
                                                                  name="name" id="example-text-input"/>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم
                                                    الخدمة باللغة الانجليزية</label>
                                                <div class="col-sm-10">
                                                    <x-form.input type="text"
                                                                  value="{{ old('name', $choice->name_en) }}"
                                                                  name="name_en" id="example-text-input"/>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">سعر
                                                    الخدمة</label>
                                                <div class="col-sm-10">
                                                    <x-form.input type="text"
                                                                  value="{{ old('name', $choice->service_price) }}"
                                                                  name="service_price"
                                                                  id="example-text-input"/>
                                                </div>
                                            </div>


                                            <x-dashboard.image-preview image="{{ $choice->image_url }}"
                                                                       fileName="image" width="150"
                                                                       heigh="150" title="الصورة "/>


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

@endsection

