@extends('dashboard.index')

@section('title', 'تعديل سيارة')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('cars.index') }}">السيارات</a></li>
    <li class="breadcrumb-item">تعديل سيارة</li>
@endsection

@section('section')
    <style>
        .car-form-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .car-form-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 12px;
            overflow: hidden;
        }
        .car-form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .car-form-header i {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .car-form-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
        }
        .car-form-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        .car-id-badge {
            background: rgba(255,255,255,0.2);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-block;
            margin-top: 10px;
        }
        .form-section {
            padding: 30px;
        }
        .section-divider {
            border-bottom: 2px solid #e9ecef;
            margin: 30px 0;
            padding-bottom: 15px;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .section-title i {
            color: #667eea;
            font-size: 1.3rem;
        }
        .form-group-modern {
            margin-bottom: 25px;
        }
        .form-label-modern {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }
        .form-label-modern i {
            color: #667eea;
            font-size: 1rem;
        }
        .form-label-modern .required {
            color: #dc3545;
            margin-right: 3px;
        }
        .form-control-modern {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        .form-control-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        .language-badge {
            background: #e9ecef;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            margin-right: 5px;
        }
        .language-badge.ar {
            background: #d4edda;
            color: #155724;
        }
        .language-badge.en {
            background: #d1ecf1;
            color: #0c5460;
        }
        .switch-modern {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }
        .switch-modern input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider-modern {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 30px;
        }
        .slider-modern:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        input:checked + .slider-modern:before {
            transform: translateX(30px);
        }
        .status-label {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .status-text {
            font-weight: 600;
            color: #495057;
        }
        .btn-update-car {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        .btn-update-car:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-cancel-car {
            background: #6c757d;
            border: none;
            padding: 12px 40px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-cancel-car:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
        }
        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #0066cc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .info-box i {
            color: #0066cc;
            margin-left: 10px;
        }
        .current-value-badge {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #6c757d;
            display: inline-block;
            margin-top: 5px;
        }
    </style>

    <div class="car-form-container">
        <div class="card car-form-card">
            <!-- Header -->
            <div class="car-form-header">
                <i class="mdi mdi-car-cog"></i>
                <h4>تعديل بيانات السيارة</h4>
                <p>تحديث معلومات السيارة</p>
                <div class="car-id-badge">
                    <i class="mdi mdi-identifier"></i>
                    رقم السيارة: #{{ $car->id }}
                </div>
            </div>

            <!-- Form -->
            <div class="form-section">
                <form action="{{ route('cars.update', $car->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <!-- Errors Alert -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-custom">
                            <strong><i class="mdi mdi-alert-circle"></i> يرجى تصحيح الأخطاء التالية:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Info Box -->
                    <div class="info-box">
                        <i class="mdi mdi-information"></i>
                        <strong>ملاحظة:</strong> الحقول المميزة بـ <span class="text-danger">*</span> إلزامية
                    </div>

                    <!-- Brand Section -->
                    <div class="section-title">
                        <i class="mdi mdi-car-info"></i>
                        معلومات الماركة
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="mdi mdi-tag"></i>
                                    <span class="language-badge ar">عربي</span>
                                    الماركة
                                    <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       name="brand_ar" 
                                       class="form-control form-control-modern" 
                                       value="{{ old('brand_ar', $car->brand_ar) }}" 
                                       placeholder="مثال: تويوتا، هوندا، نيسان"
                                       required>
                                @if($car->brand_ar)
                                    <div class="current-value-badge">
                                        <i class="mdi mdi-information-outline"></i>
                                        القيمة الحالية: {{ $car->brand_ar }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="mdi mdi-tag"></i>
                                    <span class="language-badge en">English</span>
                                    Brand
                                </label>
                                <input type="text" 
                                       name="brand_en" 
                                       class="form-control form-control-modern" 
                                       value="{{ old('brand_en', $car->brand_en) }}" 
                                       placeholder="Example: Toyota, Honda, Nissan">
                                @if($car->brand_en)
                                    <div class="current-value-badge">
                                        <i class="mdi mdi-information-outline"></i>
                                        Current: {{ $car->brand_en }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Model Section -->
                    <div class="section-divider">
                        <div class="section-title">
                            <i class="mdi mdi-car-side"></i>
                            معلومات الموديل
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="mdi mdi-car"></i>
                                    <span class="language-badge ar">عربي</span>
                                    الموديل
                                    <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       name="model_ar" 
                                       class="form-control form-control-modern" 
                                       value="{{ old('model_ar', $car->model_ar) }}" 
                                       placeholder="مثال: كامري، أكورد، ألتيما"
                                       required>
                                @if($car->model_ar)
                                    <div class="current-value-badge">
                                        <i class="mdi mdi-information-outline"></i>
                                        القيمة الحالية: {{ $car->model_ar }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="mdi mdi-car"></i>
                                    <span class="language-badge en">English</span>
                                    Model
                                </label>
                                <input type="text" 
                                       name="model_en" 
                                       class="form-control form-control-modern" 
                                       value="{{ old('model_en', $car->model_en) }}" 
                                       placeholder="Example: Camry, Accord, Altima">
                                @if($car->model_en)
                                    <div class="current-value-badge">
                                        <i class="mdi mdi-information-outline"></i>
                                        Current: {{ $car->model_en }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details Section -->
                    <div class="section-divider">
                        <div class="section-title">
                            <i class="mdi mdi-information-variant"></i>
                            تفاصيل إضافية
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="mdi mdi-calendar"></i>
                                    سنة الصنع
                                </label>
                                <input type="text" 
                                       name="year" 
                                       class="form-control form-control-modern" 
                                       value="{{ old('year', $car->year) }}" 
                                       placeholder="مثال: 2024"
                                       maxlength="4">
                                @if($car->year)
                                    <div class="current-value-badge">
                                        <i class="mdi mdi-information-outline"></i>
                                        القيمة الحالية: {{ $car->year }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="mdi mdi-palette"></i>
                                    اللون
                                </label>
                                <input type="text" 
                                       name="color" 
                                       class="form-control form-control-modern" 
                                       value="{{ old('color', $car->color) }}" 
                                       placeholder="مثال: أبيض، أسود، فضي">
                                @if($car->color)
                                    <div class="current-value-badge">
                                        <i class="mdi mdi-information-outline"></i>
                                        القيمة الحالية: {{ $car->color }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="section-divider">
                        <div class="section-title">
                            <i class="mdi mdi-toggle-switch"></i>
                            حالة السيارة
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <div class="status-label">
                            <label class="switch-modern">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $car->is_active) ? 'checked' : '' }}>
                                <span class="slider-modern"></span>
                            </label>
                            <span class="status-text">
                                <i class="mdi mdi-check-circle text-success"></i>
                                السيارة نشطة ومتاحة
                            </span>
                        </div>
                        <small class="text-muted d-block mt-2 mr-5">
                            عند التفعيل، ستظهر السيارة في قائمة السيارات المتاحة للعملاء
                        </small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center mt-4 pt-3">
                        <button type="submit" class="btn btn-update-car">
                            <i class="mdi mdi-content-save me-2"></i>
                            تحديث البيانات
                        </button>
                        <a href="{{ route('cars.index') }}" class="btn btn-cancel-car ms-2">
                            <i class="mdi mdi-close me-2"></i>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
