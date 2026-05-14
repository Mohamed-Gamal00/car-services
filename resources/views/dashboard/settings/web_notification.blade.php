@extends('dashboard.index')

@section('title', 'إرسال إشعارات')

@section('css')
<style>
    .notification-card {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: none;
    }
    
    .notification-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 12px 12px 0 0;
    }
    
    .target-option {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .target-option:hover {
        border-color: #667eea;
        background: #f8f9fc;
    }
    
    .target-option.active {
        border-color: #667eea;
        background: linear-gradient(135deg, #f0f4ff 0%, #e8edff 100%);
    }
    
    .target-option input[type="radio"] {
        margin-left: 10px;
    }
    
    .btn-send {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
    }
    
    .btn-test {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
    }
</style>
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">الإعدادات</li>
    <li class="breadcrumb-item active">إرسال إشعارات</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card notification-card">
                <div class="notification-header">
                    <h4 class="mb-0">
                        <i class="mdi mdi-bell-ring"></i>
                        إرسال إشعار Push Notification
                    </h4>
                    <p class="mb-0 mt-2" style="opacity: 0.9;">أرسل إشعارات فورية إلى الأجهزة المسجلة</p>
                </div>
                
                <div class="card-body">
                    <x-alert type='success'/>
                    <x-alert type='error'/>
                    
                    <form class="form form-vertical" action="{{ route('notification.Dashboard.store') }}" method="post" id="notificationForm">
                        @csrf

                        <div class="form-body">
                            {{-- Target Selection --}}
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label class="form-label fw-bold">
                                        <i class="mdi mdi-target"></i>
                                        إرسال إلى:
                                    </label>
                                    
                                    <div class="target-option active" onclick="selectTarget('topic', this)">
                                        <input type="radio" name="target_type" value="topic" id="target_topic" checked>
                                        <label for="target_topic" style="cursor: pointer; margin: 0;">
                                            <strong>الموضوع العام (General Topic)</strong>
                                            <br>
                                            <small class="text-muted">إرسال إلى جميع المستخدمين المشتركين في الموضوع العام</small>
                                        </label>
                                    </div>
                                    
                                    <div class="target-option" onclick="selectTarget('all_admins', this)">
                                        <input type="radio" name="target_type" value="all_admins" id="target_all_admins">
                                        <label for="target_all_admins" style="cursor: pointer; margin: 0;">
                                            <strong>جميع المسؤولين</strong>
                                            <br>
                                            <small class="text-muted">إرسال إلى جميع أجهزة المسؤولين المسجلة</small>
                                        </label>
                                    </div>
                                    
                                    <div class="target-option" onclick="selectTarget('admin', this)">
                                        <input type="radio" name="target_type" value="admin" id="target_admin">
                                        <label for="target_admin" style="cursor: pointer; margin: 0;">
                                            <strong>مسؤول محدد</strong>
                                            <br>
                                            <small class="text-muted">إرسال إلى مسؤول معين</small>
                                        </label>
                                        
                                        <div id="admin_select_wrapper" style="display: none; margin-top: 15px;">
                                            <select name="admin_id" id="admin_id" class="form-control">
                                                <option value="">اختر المسؤول</option>
                                                @foreach($admins as $admin)
                                                    <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                                                @endforeach
                                            </select>
                                            @error('admin_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Title --}}
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title" class="fw-bold">
                                            <i class="mdi mdi-format-title"></i>
                                            العنوان
                                        </label>
                                        <input type="text" id="title" class="form-control" name="title" 
                                               placeholder="عنوان الإشعار" value="{{ old('title') }}">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description" class="fw-bold">
                                            <i class="mdi mdi-text"></i>
                                            المحتوى
                                        </label>
                                        <textarea name="description" id="description" class="form-control" 
                                                  rows="4" placeholder="محتوى الإشعار">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="row">
                                <div class="col-12" style="text-align: center;">
                                    <button type="submit" class="btn btn-send">
                                        <i class="mdi mdi-send"></i>
                                        إرسال الإشعار
                                    </button>
                                    
                                    <button type="button" class="btn btn-test" onclick="sendTestNotification()">
                                        <i class="mdi mdi-test-tube"></i>
                                        إرسال تجريبي لي
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
    function selectTarget(type, element) {
        // Remove active class from all options
        document.querySelectorAll('.target-option').forEach(opt => {
            opt.classList.remove('active');
        });
        
        // Add active class to selected option
        element.classList.add('active');
        
        // Check the radio button
        document.getElementById('target_' + type).checked = true;
        
        // Show/hide admin select
        const adminWrapper = document.getElementById('admin_select_wrapper');
        if (type === 'admin') {
            adminWrapper.style.display = 'block';
        } else {
            adminWrapper.style.display = 'none';
        }
    }
    
    function sendTestNotification() {
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        
        if (!title || !description) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: 'يرجى إدخال العنوان والمحتوى',
                });
            } else {
                alert('يرجى إدخال العنوان والمحتوى');
            }
            return;
        }
        
        // Show loading
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'جاري الإرسال...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        $.ajax({
            url: '{{ route("notification.Dashboard.sendToMe") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                title: title,
                description: description
            },
            success: function(response) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح',
                        text: response.message || 'تم إرسال الإشعار التجريبي',
                    });
                } else {
                    alert('تم إرسال الإشعار التجريبي');
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'حدث خطأ أثناء الإرسال';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: message,
                    });
                } else {
                    alert(message);
                }
            }
        });
    }
</script>
@endsection
