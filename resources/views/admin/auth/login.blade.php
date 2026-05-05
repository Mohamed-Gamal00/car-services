<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة الخدمات</title>
    
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #06b6d4;
            --success-color: #10b981;
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated background elements */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .bg-animation span {
            position: absolute;
            display: block;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            animation: float 25s infinite;
            border-radius: 50%;
        }
        
        .bg-animation span:nth-child(1) { left: 25%; animation-delay: 0s; width: 80px; height: 80px; }
        .bg-animation span:nth-child(2) { left: 10%; animation-delay: 2s; width: 20px; height: 20px; }
        .bg-animation span:nth-child(3) { left: 70%; animation-delay: 4s; width: 60px; height: 60px; }
        .bg-animation span:nth-child(4) { left: 40%; animation-delay: 0s; width: 40px; height: 40px; }
        .bg-animation span:nth-child(5) { left: 65%; animation-delay: 3s; width: 30px; height: 30px; }
        .bg-animation span:nth-child(6) { left: 75%; animation-delay: 7s; width: 50px; height: 50px; }
        .bg-animation span:nth-child(7) { left: 35%; animation-delay: 5s; width: 70px; height: 70px; }
        .bg-animation span:nth-child(8) { left: 50%; animation-delay: 9s; width: 25px; height: 25px; }
        
        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) rotate(720deg); opacity: 0; }
        }
        
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }
        
        .logo-container {
            position: relative;
            margin-bottom: 20px;
        }
        
        .logo-circle {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .logo-circle img {
            max-width: 60px;
            max-height: 60px;
        }
        
        .logo-circle i {
            font-size: 45px;
            color: var(--primary-color);
        }
        
        .login-header h4 {
            color: white;
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 26px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .login-header p {
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
            font-size: 15px;
        }
        
        .login-body {
            padding: 40px 35px;
        }
        
        .service-badges {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .service-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 20px;
            font-size: 13px;
            color: #0369a1;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .service-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(3, 105, 161, 0.2);
        }
        
        .service-badge i {
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 24px;
            position: relative;
        }
        
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .form-label i {
            color: var(--primary-color);
            font-size: 16px;
        }
        
        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            background: white;
            outline: none;
        }
        
        .form-check {
            margin-bottom: 24px;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-check-label {
            color: #6b7280;
            font-size: 14px;
            cursor: pointer;
            margin-right: 8px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 14px 18px;
            margin-bottom: 24px;
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-right: 4px solid #dc2626;
        }
        
        .alert ul {
            margin: 0;
            padding-right: 20px;
        }
        
        .alert li {
            font-size: 14px;
        }
        
        .login-footer {
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }
        
        .login-footer p {
            margin: 0;
            color: #6b7280;
            font-size: 13px;
        }
        
        /* Loading animation */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.7;
        }
        
        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }
            
            .login-body {
                padding: 30px 25px;
            }
            
            .login-header {
                padding: 30px 20px;
            }
            
            .service-badges {
                gap: 10px;
            }
            
            .service-badge {
                font-size: 12px;
                padding: 6px 12px;
            }
        }
    </style>
</head>

<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="logo-container">
                    <div class="logo-circle">
                        @if(file_exists(public_path('assets/images/logo-sm.png')))
                            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="Logo">
                        @else
                            <i class="mdi mdi-cog-outline"></i>
                        @endif
                    </div>
                </div>
                <h4>نظام إدارة الخدمات</h4>
                <p>مرحباً بعودتك! قم بتسجيل الدخول للمتابعة</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <!-- Service Badges -->
                <div class="service-badges">
                    <div class="service-badge">
                        <i class="mdi mdi-shield-check"></i>
                        <span>آمن</span>
                    </div>
                    <div class="service-badge">
                        <i class="mdi mdi-lightning-bolt"></i>
                        <span>سريع</span>
                    </div>
                    <div class="service-badge">
                        <i class="mdi mdi-account-group"></i>
                        <span>موثوق</span>
                    </div>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label" for="email">
                            <i class="mdi mdi-email-outline"></i>
                            البريد الإلكتروني
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               placeholder="admin@example.com" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">
                            <i class="mdi mdi-lock-outline"></i>
                            كلمة المرور
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               placeholder="••••••••" 
                               required>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="remember" 
                               name="remember">
                        <label class="form-check-label" for="remember">
                            تذكرني لمدة 30 يوماً
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="mdi mdi-login me-2"></i>
                        تسجيل الدخول
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <p>
                    <i class="mdi mdi-shield-lock-outline"></i>
                    جميع الحقوق محفوظة © {{ date('Y') }} - نظام إدارة الخدمات
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Add loading animation on form submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('.btn-login');
            btn.classList.add('loading');
            btn.innerHTML = '<span style="opacity: 0;">جاري تسجيل الدخول...</span>';
        });

        // Add focus animation to inputs
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateX(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateX(0)';
            });
        });
    </script>
</body>
</html>