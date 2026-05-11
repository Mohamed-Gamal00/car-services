@extends('dashboard.index')
@section('title', 'تعديل بيانات الموظف')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('captains.index')}}">الموظفين</a></li>
    <li class="breadcrumb-item">تعديل البيانات</li>
@endsection

@section('section')
    <style>
        .form-container { max-width: 900px; margin: 0 auto; }
        .form-card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 12px; overflow: hidden; }
        .form-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .form-header i { font-size: 3rem; margin-bottom: 10px; }
        .form-header h4 { margin: 0; font-weight: 700; }
        .form-group-modern { margin-bottom: 25px; }
        .form-label-modern { font-weight: 600; color: #495057; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
        .form-label-modern i { color: #667eea; }
        .form-control-modern { border: 2px solid #e9ecef; border-radius: 10px; padding: 12px 15px; transition: all 0.3s; }
        .form-control-modern:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15); }
        .btn-save { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 40px; border-radius: 25px; color: white; font-weight: 600; transition: all 0.3s; }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4); color: white; }
        .section-divider { border-bottom: 2px solid #e9ecef; margin: 30px 0; padding-bottom: 15px; }
        .section-title { font-size: 1.1rem; font-weight: 700; color: #2c3e50; display: flex; align-items: center; gap: 10px; }
        .section-title i { color: #667eea; font-size: 1.3rem; }
    </style>

    <div class="form-container">
        <div class="card form-card">
            <div class="form-header">
                <i class="mdi mdi-account-edit"></i>
                <h4>تعديل بيانات الموظف</h4>
                <p class="mb-0 mt-2" style="opacity: 0.9;">تحديث معلومات الموظف</p>
            </div>

            <div class="card-body p-4">
                <x-alert type="success"/>
                <x-alert type="dark"/>
                <x-alert type="danger"/>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="post" action="{{ route('captains.update', $captain->id) }}">
                    @csrf
                    @method('put')

                    <div class="section-title">
                        <i class="mdi mdi-account-details"></i>
                        المعلومات الأساسية
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="mdi mdi-account"></i>
                                    الاسم
                                </label>
                                <input type="text" name="name" class="form-control form-control-modern" 
                                       value="{{$captain->name}}" placeholder="أدخل الاسم">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="mdi mdi-phone"></i>
                                    رقم الجوال
                                </label>
                                <input type="number" name="phone" class="form-control form-control-modern" 
                                       value="{{$captain->phone}}" placeholder="05xxxxxxxx">
                            </div>
                        </div>
                    </div>

                    <div class="section-divider">
                        <div class="section-title">
                            <i class="mdi mdi-toggle-switch"></i>
                            الحالة والنشاط
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="mdi mdi-power"></i>
                                    حالة النشاط
                                </label>
                                <select name="is_active" class="form-control form-control-modern">
                                    <option value="1" {{ $captain->is_active == 1 ? 'selected' : '' }}>نشط</option>
                                    <option value="0" {{ $captain->is_active == 0 ? 'selected' : '' }}>غير نشط</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="mdi mdi-briefcase"></i>
                                    حالة العمل
                                </label>
                                <select name="status" class="form-control form-control-modern">
                                    <option value="available" {{ $captain->status == 'available' ? 'selected' : '' }}>متاح</option>
                                    <option value="busy" {{ $captain->status == 'busy' ? 'selected' : '' }}>مشغول</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-save">
                            <i class="mdi mdi-content-save me-2"></i>
                            تحديث البيانات
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Change Section -->
        <div class="card form-card mt-4">
            <div class="form-header">
                <i class="mdi mdi-lock-reset"></i>
                <h4>تغيير كلمة المرور</h4>
            </div>

            <div class="card-body p-4">
                <form method="post" action="{{ route('captain.update_password', $captain->id) }}">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="mdi mdi-lock"></i>
                                    كلمة المرور الجديدة
                                </label>
                                <input type="password" name="password" class="form-control form-control-modern" 
                                       placeholder="أدخل كلمة المرور الجديدة">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="mdi mdi-lock-check"></i>
                                    تأكيد كلمة المرور
                                </label>
                                <input type="password" name="password_confirmation" class="form-control form-control-modern" 
                                       placeholder="أعد إدخال كلمة المرور">
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-save">
                            <i class="mdi mdi-lock-reset me-2"></i>
                            تحديث كلمة المرور
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
