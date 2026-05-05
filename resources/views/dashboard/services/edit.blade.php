@extends('dashboard.index')

@section('title', 'تعديل الخدمة')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('services.index') }}">الخدمات</a></li>
    <li class="breadcrumb-item active">تعديل: {{ $service->name }}</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">تعديل الخدمة: {{ $service->name }}</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>يوجد أخطاء في النموذج:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('services.update', $service->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">المعلومات الأساسية</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">اسم الخدمة (عربي) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   name="name" value="{{ old('name', $service->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">اسم الخدمة (English)</label>
                                            <input type="text" class="form-control @error('name_en') is-invalid @enderror" 
                                                   name="name_en" value="{{ old('name_en', $service->name_en) }}">
                                            @error('name_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">الوصف (عربي)</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      rows="4" name="description">{{ old('description', $service->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">الوصف (English)</label>
                                            <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                                      rows="4" name="description_en">{{ old('description_en', $service->description_en) }}</textarea>
                                            @error('description_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">التسعير والمدة</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">السعر (ريال) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" 
                                                       class="form-control @error('price') is-invalid @enderror" 
                                                       name="price" value="{{ old('price', $service->price) }}" required>
                                                <span class="input-group-text">ريال</span>
                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">المدة <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('duration') is-invalid @enderror" 
                                                   name="duration" 
                                                   value="{{ old('duration', $service->duration) }}" 
                                                   placeholder="HH:MM"
                                            <small class="text-muted">الصيغة: HH:MM (مثال: 01:30 = ساعة ونصف)</small>
                                            @error('duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">ترتيب العرض</label>
                                            <input type="number" min="0" class="form-control @error('sort_order') is-invalid @enderror" 
                                                   name="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}">
                                            <small class="text-muted">الخدمات ذات الترتيب الأقل تظهر أولاً</small>
                                            @error('sort_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">الحالة</label>
                                            <select class="form-select @error('is_active') is-invalid @enderror" name="is_active">
                                                <option value="1" {{ old('is_active', $service->is_active) == 1 ? 'selected' : '' }}>نشط</option>
                                                <option value="0" {{ old('is_active', $service->is_active) == 0 ? 'selected' : '' }}>غير نشط</option>
                                            </select>
                                            @error('is_active')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">الصور</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($service->image)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">الصورة الحالية</label>
                                            <div>
                                                <img src="{{ $service->image_url }}" alt="{{ $service->name }}" 
                                                     class="img-thumbnail" style="max-width: 200px;">
                                            </div>
                                        </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">تغيير صورة الخدمة</label>
                                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                                   name="image" accept="image/*" onchange="previewImage(event, 'imagePreview')">
                                            <small class="text-muted">الحد الأقصى: 2 ميجابايت | الصيغ المدعومة: JPG, PNG, GIF, SVG</small>
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="imagePreview" src="#" alt="معاينة الصورة" 
                                                     class="img-thumbnail" style="max-width: 200px; display: none;">
                                            </div>
                                        </div>

                                        @if($service->icon)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">الأيقونة الحالية</label>
                                            <div>
                                                <img src="{{ $service->icon_url }}" alt="Icon" 
                                                     class="img-thumbnail" style="max-width: 100px;">
                                            </div>
                                        </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">تغيير الأيقونة</label>
                                            <input type="file" class="form-control @error('icon') is-invalid @enderror" 
                                                   name="icon" accept="image/*" onchange="previewImage(event, 'iconPreview')">
                                            <small class="text-muted">أيقونة صغيرة للعرض في القوائم</small>
                                            @error('icon')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="iconPreview" src="#" alt="معاينة الأيقونة" 
                                                     class="img-thumbnail" style="max-width: 100px; display: none;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('services.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i> إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> تحديث الخدمة
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function previewImage(event, previewId) {
        const reader = new FileReader();
        const preview = document.getElementById(previewId);
        
        reader.onload = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
        }
        
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }    
</script>
@endsection