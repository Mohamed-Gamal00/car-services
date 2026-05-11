@extends('dashboard.index')
@section('title', 'تعديل بيانات العميل')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('clients.index')}}">العملاء</a></li>
    <li class="breadcrumb-item">تعديل البيانات</li>
@endsection

@section('section')
    <style>
        .edit-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 12px;
            overflow: hidden;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
            border: none;
        }
        .card-header-custom h5 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-group-modern {
            margin-bottom: 25px;
        }
        .form-label-modern {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
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
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .package-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }
        .package-info-item {
            background: rgba(255,255,255,0.15);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            backdrop-filter: blur(10px);
        }
        .package-info-item h6 {
            margin: 0 0 5px 0;
            opacity: 0.9;
            font-size: 0.875rem;
        }
        .package-info-item .value {
            font-size: 1.3rem;
            font-weight: 700;
        }
        .password-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .input-icon {
            color: #667eea;
        }
    </style>

    <div class="row">
        <!-- Client Information Card -->
        <div class="col-lg-8">
            <div class="card edit-card">
                <div class="card-header-custom">
                    <h5>
                        <i class="mdi mdi-account-edit"></i>
                        تعديل بيانات العميل
                    </h5>
                </div>
                <div class="card-body p-4">
                    <x-alert type="success"/>
                    <x-alert type="dark"/>

                    <form method="post" action="{{ route('clients.update', $client->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="mdi mdi-account input-icon"></i>
                                الاسم الأول
                            </label>
                            <input type="text" 
                                   name="first_name" 
                                   class="form-control form-control-modern" 
                                   value="{{$client->first_name}}"
                                   placeholder="أدخل الاسم الأول">
                            @error('first_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="mdi mdi-account input-icon"></i>
                                الاسم الأخير
                            </label>
                            <input type="text" 
                                   name="family_name" 
                                   class="form-control form-control-modern" 
                                   value="{{$client->family_name}}"
                                   placeholder="أدخل الاسم الأخير">
                            @error('family_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="mdi mdi-phone input-icon"></i>
                                رقم الجوال
                            </label>
                            <input type="number" 
                                   name="phone_number" 
                                   class="form-control form-control-modern" 
                                   value="{{$client->phone_number}}"
                                   placeholder="أدخل رقم الجوال">
                            @error('phone_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-save" type="submit">
                                <i class="mdi mdi-content-save me-2"></i>
                                حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Package Information Card -->
        <div class="col-lg-4">
            @if($package)
                <div class="card package-card">
                    <div class="card-body p-4">
                        <h5 class="mb-4">
                            <i class="mdi mdi-package-variant me-2"></i>
                            معلومات الباقة
                        </h5>
                        
                        <div class="package-info-item">
                            <h6>اسم الباقة</h6>
                            <div class="value">{{$package->name}}</div>
                        </div>

                        <div class="package-info-item">
                            <h6>عدد مرات الغسيل</h6>
                            <div class="value">
                                <i class="mdi mdi-car-wash me-2"></i>
                                {{$package->wash_count}} مرة
                            </div>
                        </div>

                        <div class="package-info-item">
                            <h6>مدة الصلاحية</h6>
                            <div class="value">
                                <i class="mdi mdi-calendar-clock me-2"></i>
                                {{$package->validity_days}} يوم
                            </div>
                        </div>

                        <div class="package-info-item">
                            <h6>السعر</h6>
                            <div class="value">
                                <i class="mdi mdi-cash me-2"></i>
                                {{$package->price}} ريال
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card edit-card">
                    <div class="card-body text-center p-5">
                        <i class="mdi mdi-package-variant-closed" style="font-size: 3rem; color: #dee2e6;"></i>
                        <h6 class="text-muted mt-3">لا توجد باقة نشطة</h6>
                        <p class="text-muted small">لم يشترك العميل في أي باقة حالياً</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Password Change Section -->
    @can('client.edit')
        <div class="row mt-4">
            <div class="col-12">
                <div class="card edit-card">
                    <div class="card-header-custom">
                        <h5>
                            <i class="mdi mdi-lock-reset"></i>
                            تغيير كلمة المرور
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="{{ route('client.update_password', $client->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-lock input-icon"></i>
                                            كلمة المرور الجديدة
                                        </label>
                                        <input type="password" 
                                               name="new_password" 
                                               class="form-control form-control-modern" 
                                               placeholder="أدخل كلمة المرور الجديدة">
                                        @error('new_password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="mdi mdi-lock-check input-icon"></i>
                                            تأكيد كلمة المرور
                                        </label>
                                        <input type="password" 
                                               name="new_password_confirmation" 
                                               class="form-control form-control-modern" 
                                               placeholder="أعد إدخال كلمة المرور">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button class="btn btn-save" type="submit">
                                    <i class="mdi mdi-lock-reset me-2"></i>
                                    تحديث كلمة المرور
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
