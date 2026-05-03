@extends('dashboard.index')

@section('title', 'تعديل سيارة')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('cars.index') }}">السيارات</a></li>
    <li class="breadcrumb-item">تعديل سيارة</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('cars.update', $car->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">الماركة (عربي) <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="brand_ar" :value="old('brand_ar', $car->brand_ar)" placeholder="مثال: تويوتا"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">الماركة (English)</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="brand_en" :value="old('brand_en', $car->brand_en)" placeholder="Example: Toyota"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">الموديل (عربي) <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="model_ar" :value="old('model_ar', $car->model_ar)" placeholder="مثال: كامري"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">الموديل (English)</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="model_en" :value="old('model_en', $car->model_en)" placeholder="Example: Camry"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">السنة</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="year" :value="old('year', $car->year)" placeholder="مثال: 2024"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">اللون</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" name="color" :value="old('color', $car->color)" placeholder="مثال: أبيض"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">الحالة</label>
                            <div class="col-sm-10">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $car->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">نشط</label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button class="btn btn-primary" type="submit">تحديث</button>
                            <a href="{{ route('cars.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
