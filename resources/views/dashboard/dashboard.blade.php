@extends('dashboard.index')

@section('title', 'لوحة التحكم')

@section('css')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        --danger-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .dashboard-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .dashboard-header p {
        font-size: 16px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--card-color);
    }

    .stat-card.primary::before { background: linear-gradient(180deg, #667eea 0%, #764ba2 100%); }
    .stat-card.success::before { background: linear-gradient(180deg, #11998e 0%, #38ef7d 100%); }
    .stat-card.info::before { background: linear-gradient(180deg, #4facfe 0%, #00f2fe 100%); }
    .stat-card.warning::before { background: linear-gradient(180deg, #fa709a 0%, #fee140 100%); }
    .stat-card.danger::before { background: linear-gradient(180deg, #f093fb 0%, #f5576c 100%); }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 15px;
        background: var(--icon-bg);
        color: var(--icon-color);
    }

    .stat-card.primary .stat-icon { background: rgba(102, 126, 234, 0.1); color: #667eea; }
    .stat-card.success .stat-icon { background: rgba(17, 153, 142, 0.1); color: #11998e; }
    .stat-card.info .stat-icon { background: rgba(79, 172, 254, 0.1); color: #4facfe; }
    .stat-card.warning .stat-icon { background: rgba(250, 112, 154, 0.1); color: #fa709a; }
    .stat-card.danger .stat-icon { background: rgba(245, 87, 108, 0.1); color: #f5576c; }

    .stat-label {
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .stat-meta {
        font-size: 13px;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .stat-badge.success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-badge.warning {
        background: rgba(251, 191, 36, 0.1);
        color: #fbbf24;
    }

    .stat-link {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 12px;
        background: rgba(0, 0, 0, 0.02);
        text-align: center;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        text-decoration: none;
        transition: all 0.3s;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stat-link:hover {
        background: rgba(0, 0, 0, 0.04);
        color: #374151;
    }

    .quick-actions {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
    }

    .quick-actions h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 20px;
        border-radius: 12px;
        background: white;
        border: 2px solid #e5e7eb;
        text-decoration: none;
        color: #374151;
        font-weight: 600;
        transition: all 0.3s;
        margin-bottom: 12px;
    }

    .action-btn:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
        color: #667eea;
        transform: translateX(-5px);
    }

    .action-btn i {
        font-size: 24px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title::before {
        content: '';
        width: 4px;
        height: 24px;
        background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            padding: 25px;
        }

        .dashboard-header h1 {
            font-size: 24px;
        }

        .stat-value {
            font-size: 24px;
        }
    }
</style>
@endsection

@section('breadcrumb')
    @parent
@endsection

@section('section')

    {{-- Dashboard Header --}}
    <div class="dashboard-header">
        <h1>
            <i class="mdi mdi-view-dashboard"></i>
            مرحباً بك في لوحة التحكم
        </h1>
        <p>نظرة شاملة على أداء المنصة والإحصائيات الرئيسية</p>
    </div>

    {{-- Main Statistics --}}
    <h2 class="section-title">الإحصائيات الرئيسية</h2>
    
    <div class="row">
        {{-- Services --}}
        <div class="col-xl-3 col-md-6">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="mdi mdi-car-wash"></i>
                </div>
                <div class="stat-label">إجمالي الخدمات</div>
                <div class="stat-value">{{ $servicesCount }}</div>
                <div class="stat-meta">
                    <i class="mdi mdi-check-circle text-success"></i>
                    <span>خدمات نشطة</span>
                </div>
                <a href="{{ route('services.index') }}" class="stat-link">
                    عرض جميع الخدمات
                    <i class="mdi mdi-arrow-left"></i>
                </a>
            </div>
        </div>

        {{-- Packages --}}
        <div class="col-xl-3 col-md-6">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="mdi mdi-package-variant"></i>
                </div>
                <div class="stat-label">الباقات المتاحة</div>
                <div class="stat-value">{{ $packagesCount }}</div>
                <div class="stat-meta">
                    <i class="mdi mdi-star text-warning"></i>
                    <span>باقات مميزة</span>
                </div>
                <a href="{{ route('packages.index') }}" class="stat-link">
                    إدارة الباقات
                    <i class="mdi mdi-arrow-left"></i>
                </a>
            </div>
        </div>

        {{-- Orders --}}
        <div class="col-xl-3 col-md-6">
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="mdi mdi-cart"></i>
                </div>
                <div class="stat-label">إجمالي الطلبات</div>
                <div class="stat-value">{{ $ordersCount }}</div>
                <div class="stat-meta">
                    <span class="stat-badge success">
                        <i class="mdi mdi-trending-up"></i>
                        اليوم: {{ $todayOrders }}
                    </span>
                </div>
                <a href="{{ route('orders.index') }}" class="stat-link">
                    عرض الطلبات
                    <i class="mdi mdi-arrow-left"></i>
                </a>
            </div>
        </div>

        {{-- Customers --}}
        <div class="col-xl-3 col-md-6">
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="mdi mdi-account-group"></i>
                </div>
                <div class="stat-label">العملاء المسجلين</div>
                <div class="stat-value">{{ $usersCount }}</div>
                <div class="stat-meta">
                    <i class="mdi mdi-account-check text-success"></i>
                    <span>عملاء نشطين</span>
                </div>
                <a href="{{ route('clients.index') }}" class="stat-link">
                    إدارة العملاء
                    <i class="mdi mdi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Operations Statistics --}}
    <h2 class="section-title">إحصائيات العمليات</h2>
    
    <div class="row">
        {{-- Captains --}}
        <div class="col-xl-3 col-md-6">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="mdi mdi-account-tie"></i>
                </div>
                <div class="stat-label">الكباتن</div>
                <div class="stat-value">{{ $captainsCount }}</div>
                <div class="stat-meta">
                    <span class="stat-badge success">
                        <i class="mdi mdi-check"></i>
                        متاح: {{ $availableCaptains }}
                    </span>
                    <span class="stat-badge warning">
                        <i class="mdi mdi-clock"></i>
                        مشغول: {{ $busyCaptains }}
                    </span>
                </div>
                <a href="{{ route('captains.index') }}" class="stat-link">
                    إدارة الكباتن
                    <i class="mdi mdi-arrow-left"></i>
                </a>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="col-xl-3 col-md-6">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="mdi mdi-cash-multiple"></i>
                </div>
                <div class="stat-label">إجمالي الإيرادات</div>
                <div class="stat-value">{{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-meta">
                    <i class="mdi mdi-currency-usd"></i>
                    <span>ريال سعودي</span>
                </div>
                <a href="{{ route('payments.index') }}" class="stat-link">
                    عرض المدفوعات
                    <i class="mdi mdi-arrow-left"></i>
                </a>
            </div>
        </div>

        {{-- Admins --}}
        <div class="col-xl-3 col-md-6">
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="mdi mdi-shield-account"></i>
                </div>
                <div class="stat-label">المدراء</div>
                <div class="stat-value">{{ $adminsCount }}</div>
                <div class="stat-meta">
                    <i class="mdi mdi-security"></i>
                    <span>مدراء النظام</span>
                </div>
                <a href="{{ route('admins.index') }}" class="stat-link">
                    إدارة المدراء
                    <i class="mdi mdi-arrow-left"></i>
                </a>
            </div>
        </div>

        {{-- Messages --}}
        <div class="col-xl-3 col-md-6">
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="mdi mdi-email"></i>
                </div>
                <div class="stat-label">رسائل التواصل</div>
                <div class="stat-value">{{ $messagesCount }}</div>
                <div class="stat-meta">
                    <i class="mdi mdi-message-alert text-danger"></i>
                    <span>رسائل جديدة</span>
                </div>
                <a href="{{ route('contact_us.index') }}" class="stat-link">
                    عرض الرسائل
                    <i class="mdi mdi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="quick-actions">
                <h3>
                    <i class="mdi mdi-lightning-bolt text-warning"></i>
                    إجراءات سريعة
                </h3>
                
                <a href="{{ route('orders.index') }}" class="action-btn">
                    <i class="mdi mdi-cart-plus"></i>
                    <div>
                        <div style="font-size: 14px; font-weight: 700;">إدارة الطلبات</div>
                        <div style="font-size: 12px; color: #9ca3af;">عرض وإدارة جميع الطلبات</div>
                    </div>
                </a>

                <a href="{{ route('services.create') }}" class="action-btn">
                    <i class="mdi mdi-plus-circle"></i>
                    <div>
                        <div style="font-size: 14px; font-weight: 700;">إضافة خدمة جديدة</div>
                        <div style="font-size: 12px; color: #9ca3af;">إنشاء خدمة تنظيف جديدة</div>
                    </div>
                </a>

                <a href="{{ route('captains.create') }}" class="action-btn">
                    <i class="mdi mdi-account-plus"></i>
                    <div>
                        <div style="font-size: 14px; font-weight: 700;">إضافة كابتن</div>
                        <div style="font-size: 12px; color: #9ca3af;">تسجيل كابتن جديد</div>
                    </div>
                </a>

                <a href="{{ route('discount_code.create') }}" class="action-btn">
                    <i class="mdi mdi-ticket-percent"></i>
                    <div>
                        <div style="font-size: 14px; font-weight: 700;">إنشاء كود خصم</div>
                        <div style="font-size: 12px; color: #9ca3af;">إضافة كوبون خصم جديد</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="quick-actions">
                <h3>
                    <i class="mdi mdi-cog text-primary"></i>
                    الإعدادات والتقارير
                </h3>
                
                <a href="{{ route('reports.index') }}" class="action-btn">
                    <i class="mdi mdi-chart-line"></i>
                    <div>
                        <div style="font-size: 14px; font-weight: 700;">التقارير والإحصائيات</div>
                        <div style="font-size: 12px; color: #9ca3af;">عرض تقارير الأداء</div>
                    </div>
                </a>

                <a href="{{ route('notification.Dashboard.create') }}" class="action-btn">
                    <i class="mdi mdi-bell-ring"></i>
                    <div>
                        <div style="font-size: 14px; font-weight: 700;">إرسال إشعار</div>
                        <div style="font-size: 12px; color: #9ca3af;">إرسال إشعار للمستخدمين</div>
                    </div>
                </a>

                <a href="{{ route('settings') }}" class="action-btn">
                    <i class="mdi mdi-cog-outline"></i>
                    <div>
                        <div style="font-size: 14px; font-weight: 700;">إعدادات النظام</div>
                        <div style="font-size: 12px; color: #9ca3af;">تعديل إعدادات المنصة</div>
                    </div>
                </a>

                <a href="{{ route('designs.index') }}" class="action-btn">
                    <i class="mdi mdi-image-multiple"></i>
                    <div>
                        <div style="font-size: 14px; font-weight: 700;">إدارة البنرات</div>
                        <div style="font-size: 12px; color: #9ca3af;">تعديل البنرات الإعلانية</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

@endsection
