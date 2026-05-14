@extends('dashboard.index')
@section('title', 'إضافة موظف جديد')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('captains.index')}}">الموظفين</a></li>
    <li class="breadcrumb-item">إضافة موظف جديد</li>
@endsection

@section('section')
    <style>
        .form-container { max-width: 800px; margin: 0 auto; }
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
    </style>

    <div class="form-container">
        <div class="card form-card">
            <div class="form-header">
                <i class="mdi mdi-account-plus"></i>
                <h4>إضافة موظف جديد</h4>
                <p class="mb-0 mt-2" style="opacity: 0.9;">أدخل بيانات الموظف</p>
            </div>

            <div class="card-body p-4">
                <form autocomplete="off" method="post" action="{{ route('captains.store') }}">
                    @csrf

                    <div class="form-group-modern">
                        <label class="form-label-modern">
                            <i class="mdi mdi-account"></i>
                            الاسم
                        </label>
                        <input type="text" name="name" class="form-control form-control-modern" 
                               value="{{ old('name') }}" placeholder="أدخل اسم الموظف">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern">
                            <i class="mdi mdi-phone"></i>
                            رقم الجوال
                        </label>
                        <input type="tel" name="phone" class="form-control form-control-modern" 
                               value="{{ old('phone') }}" placeholder="05xxxxxxxx">
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern">
                            <i class="mdi mdi-lock"></i>
                            كلمة المرور
                        </label>
                        <input type="password" name="password" class="form-control form-control-modern" 
                               placeholder="أدخل كلمة المرور">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern">
                            <i class="mdi mdi-lock-check"></i>
                            تأكيد كلمة المرور
                        </label>
                        <input type="password" name="password_confirmation" class="form-control form-control-modern" 
                               placeholder="أعد إدخال كلمة المرور">
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-save">
                            <i class="mdi mdi-content-save me-2"></i>
                            حفظ الموظف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
