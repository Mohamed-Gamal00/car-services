@extends('dashboard.index')

@section('title', 'الإعدادات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">الإعدادات</li>
@endsection

@section('section')
    <style>
        .settings-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .settings-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 12px;
            overflow: hidden;
        }
        .settings-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .settings-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.75rem;
        }
        .settings-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .nav-tabs-custom {
            border-bottom: 2px solid #e9ecef;
            padding: 0 30px;
            background: #f8f9fa;
        }
        .nav-tabs-custom .nav-link {
            border: none;
            color: #6c757d;
            padding: 15px 25px;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
        }
        .nav-tabs-custom .nav-link:hover {
            color: #667eea;
            background: transparent;
        }
        .nav-tabs-custom .nav-link.active {
            color: #667eea;
            background: transparent;
            border: none;
        }
        .nav-tabs-custom .nav-link.active:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 3px 3px 0 0;
        }
        .tab-content {
            padding: 30px;
        }
        .form-section {
            margin-bottom: 35px;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 10px;
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
        .time-range-group {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .time-range-group .form-control-modern {
            flex: 1;
        }
        .time-separator {
            color: #6c757d;
            font-weight: 600;
        }
        .btn-save-settings {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 14px 50px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        .btn-save-settings:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .image-upload-section {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s;
        }
        .image-upload-section:hover {
            border-color: #667eea;
            background: #f0f3ff;
        }
        .current-logo {
            max-width: 200px;
            max-height: 200px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
        .info-badge {
            background: #e7f3ff;
            color: #0066cc;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }
        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }
    </style>

    <div class="settings-container">
        <form action="{{route('settings.update', $setting->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')

            <div class="card settings-card">
                <!-- Header -->
                <div class="settings-header">
                    <i class="mdi mdi-cog" style="font-size: 3rem; margin-bottom: 10px;"></i>
                    <h4>إعدادات النظام</h4>
                    <p>إدارة وتخصيص إعدادات التطبيق والخدمات</p>
                </div>

                <!-- Alerts -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-custom m-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="px-3 pt-3">
                    <x-alert type='success'/>
                    <x-alert type='dark'/>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#general" role="tab">
                            <i class="mdi mdi-information-outline me-2"></i>
                            معلومات عامة
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#contact" role="tab">
                            <i class="mdi mdi-phone me-2"></i>
                            معلومات الاتصال
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#payment" role="tab">
                            <i class="mdi mdi-credit-card me-2"></i>
                            بوابة الدفع
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#sms" role="tab">
                            <i class="mdi mdi-message-text me-2"></i>
                            بوابة الرسائل
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#working" role="tab">
                            <i class="mdi mdi-clock-outline me-2"></i>
                            أوقات العمل
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#branding" role="tab">
                            <i class="mdi mdi-image me-2"></i>
                            الشعار
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- General Information Tab -->
                    <div class="tab-pane active" id="general" role="tabpanel">
                        <div class="form-section">
                            <div class="section-title">
                                <i class="mdi mdi-information"></i>
                                المعلومات الأساسية
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-application"></i>
                                            اسم التطبيق (عربي)
                                        </label>
                                        <input type="text" name="website_name" class="form-control form-control-modern" 
                                               value="{{$setting->website_name}}" placeholder="أدخل اسم التطبيق">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-application"></i>
                                            اسم التطبيق (English)
                                        </label>
                                        <input type="text" name="website_name_en" class="form-control form-control-modern" 
                                               value="{{$setting->website_name_en}}" placeholder="Enter app name">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-map-marker"></i>
                                            عنوان التطبيق (عربي)
                                        </label>
                                        <input type="text" name="address" class="form-control form-control-modern" 
                                               value="{{$setting->address}}" placeholder="أدخل العنوان">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-map-marker"></i>
                                            عنوان التطبيق (English)
                                        </label>
                                        <input type="text" name="address_en" class="form-control form-control-modern" 
                                               value="{{$setting->address_en}}" placeholder="Enter address">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-text"></i>
                                            وصف التطبيق (عربي)
                                        </label>
                                        <input type="text" name="subscription_title" class="form-control form-control-modern" 
                                               value="{{$setting->subscription_title}}" placeholder="أدخل الوصف">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-text"></i>
                                            وصف التطبيق (English)
                                        </label>
                                        <input type="text" name="subscription_title_en" class="form-control form-control-modern" 
                                               value="{{$setting->subscription_title_en}}" placeholder="Enter description">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Tab -->
                    <div class="tab-pane" id="contact" role="tabpanel">
                        <div class="form-section">
                            <div class="section-title">
                                <i class="mdi mdi-contacts"></i>
                                معلومات التواصل
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-email"></i>
                                            البريد الإلكتروني
                                        </label>
                                        <input type="email" name="email" class="form-control form-control-modern" 
                                               value="{{$setting->email}}" placeholder="example@domain.com">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-phone"></i>
                                            رقم الجوال
                                        </label>
                                        <input type="number" name="phone_number" class="form-control form-control-modern" 
                                               value="{{$setting->phone_number}}" placeholder="05xxxxxxxx">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-whatsapp"></i>
                                            واتساب
                                        </label>
                                        <input type="number" name="whatsaap" class="form-control form-control-modern" 
                                               value="{{$setting->whatsaap}}" placeholder="05xxxxxxxx">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Gateway Tab -->
                    <div class="tab-pane" id="payment" role="tabpanel">
                        <div class="form-section">
                            <div class="section-title">
                                <i class="mdi mdi-credit-card-settings"></i>
                                إعدادات بوابة الدفع
                            </div>

                            <div class="info-badge mb-4">
                                <i class="mdi mdi-information"></i>
                                تأكد من إدخال مفاتيح API الصحيحة من لوحة تحكم بوابة الدفع
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-key"></i>
                                            المفتاح العام (Publishable Key)
                                        </label>
                                        <input type="text" name="publishable_key" class="form-control form-control-modern" 
                                               value="{{$setting->publishable_key}}" placeholder="pk_live_...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-key-variant"></i>
                                            المفتاح السري (Secret Key)
                                        </label>
                                        <input type="text" name="secret_key" class="form-control form-control-modern" 
                                               value="{{$setting->secret_key}}" placeholder="sk_live_...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SMS Gateway Tab -->
                    <div class="tab-pane" id="sms" role="tabpanel">
                        <div class="form-section">
                            <div class="section-title">
                                <i class="mdi mdi-message-settings"></i>
                                إعدادات بوابة الرسائل النصية
                            </div>

                            <div class="info-badge mb-4">
                                <i class="mdi mdi-information"></i>
                                معلومات الاتصال ببوابة الرسائل النصية SMS
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-key"></i>
                                            مفتاح API
                                        </label>
                                        <input type="text" name="sms_api_key" class="form-control form-control-modern" 
                                               value="{{$setting->sms_api_key}}" placeholder="API Key">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-account"></i>
                                            اسم المستخدم
                                        </label>
                                        <input type="text" name="sms_user_name" class="form-control form-control-modern" 
                                               value="{{$setting->sms_user_name}}" placeholder="Username">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-card-account-details"></i>
                                            اسم المرسل
                                        </label>
                                        <input type="text" name="sernder" class="form-control form-control-modern" 
                                               value="{{$setting->sernder}}" placeholder="Sender Name">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Working Hours Tab -->
                    <div class="tab-pane" id="working" role="tabpanel">
                        <div class="form-section">
                            <div class="section-title">
                                <i class="mdi mdi-clock-time-four"></i>
                                أوقات العمل والراحة
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-clock-start"></i>
                                            ساعات العمل
                                        </label>
                                        <div class="time-range-group">
                                            <input type="text" name="working_strat_time" class="form-control form-control-modern" 
                                                   value="{{$setting->working_strat_time}}" placeholder="من (مثال: 08:00)">
                                            <span class="time-separator">إلى</span>
                                            <input type="text" name="working_end_time" class="form-control form-control-modern" 
                                                   value="{{$setting->working_end_time}}" placeholder="إلى (مثال: 18:00)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-coffee"></i>
                                            وقت الراحة
                                        </label>
                                        <div class="time-range-group">
                                            <input type="text" name="start_rest_time" class="form-control form-control-modern" 
                                                   value="{{$setting->start_rest_time}}" placeholder="من (مثال: 12:00)">
                                            <span class="time-separator">إلى</span>
                                            <input type="text" name="end_rest_time" class="form-control form-control-modern" 
                                                   value="{{$setting->end_rest_time}}" placeholder="إلى (مثال: 13:00)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Branding Tab -->
                    <div class="tab-pane" id="branding" role="tabpanel">
                        <div class="form-section">
                            <div class="section-title">
                                <i class="mdi mdi-image-area"></i>
                                شعار التطبيق
                            </div>

                            <div class="image-upload-section">
                                @if($setting->logo)
                                    <img src="{{asset('storage/' . $setting->logo)}}" alt="Logo" class="current-logo">
                                    <p class="text-muted mb-3">الشعار الحالي</p>
                                @else
                                    <i class="mdi mdi-image-plus" style="font-size: 4rem; color: #dee2e6; margin-bottom: 15px;"></i>
                                    <p class="text-muted mb-3">لا يوجد شعار محمل</p>
                                @endif
                                
                                <div class="form-group-modern">
                                    <label class="form-label-modern justify-content-center">
                                        <i class="mdi mdi-upload"></i>
                                        تحميل شعار جديد
                                    </label>
                                    <input type="file" name="logo" class="form-control form-control-modern" accept="image/*">
                                    <small class="text-muted d-block mt-2">
                                        الصيغ المدعومة: JPG, PNG, SVG | الحجم الموصى به: 200x200 بكسل
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="text-center pb-4">
                    <button type="submit" class="btn btn-save-settings">
                        <i class="mdi mdi-content-save me-2"></i>
                        حفظ جميع التعديلات
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
