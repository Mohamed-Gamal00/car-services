@extends('dashboard.index')

@section('title', 'إنشاء خدمة جديدة')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('services.index') }}">الخدمات</a></li>
    <li class="breadcrumb-item">إنشاء خدمة</li>
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

                    <form action="{{ route('services.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">اسم الخدمة (عربي)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">اسم الخدمة (English)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name_en" value="{{ old('name_en') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">الوصف (عربي)</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="4" name="description">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">الوصف (English)</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="4" name="description_en">{{ old('description_en') }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">السعر (ريال)</label>
                            <div class="col-sm-10">
                                <input type="number" step="0.01" class="form-control" name="price" value="{{ old('price') }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">المدة (HH:MM)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="duration" value="{{ old('duration', '01:00') }}" placeholder="01:30" required>
                                <small class="text-muted">مثال: 01:30 (ساعة ونصف)</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">الحالة</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="is_active">
                                    <option value="1" selected>نشط</option>
                                    <option value="0">غير نشط</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label fw-bold">الصورة</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">حفظ الخدمة</button>
                                <a href="{{ route('services.index') }}" class="btn btn-secondary">إلغاء</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
