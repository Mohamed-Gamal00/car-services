@extends('dashboard.index')

@section('title', 'تفاصيل الطلب')
@section('css')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .order-header {
            background: var(--primary-gradient);
            border-radius: 16px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .order-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .order-header-content {
            position: relative;
            z-index: 1;
        }

        .order-number {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .order-number .badge {
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .customer-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 15px;
        }

        .customer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #667eea;
            font-weight: 700;
        }

        .modern-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .modern-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .card-header-modern {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px 25px;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header-modern i {
            font-size: 24px;
            color: #667eea;
        }

        .card-header-modern h5 {
            margin: 0;
            font-weight: 700;
            color: #2d3748;
            font-size: 18px;
        }

        .card-body-modern {
            padding: 25px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: #e9ecef;
            transform: translateX(-3px);
        }

        .info-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .info-icon.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .info-icon.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .info-icon.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .info-icon.warning {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .info-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
        }

        .service-badge-large {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: var(--primary-gradient);
            color: white;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .additional-services {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 15px;
        }

        .service-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .service-chip:hover {
            border-color: #667eea;
            background: #f0f4ff;
        }

        .service-chip .price {
            color: #10b981;
            font-weight: 700;
        }

        .order-images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .order-image-wrapper {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            aspect-ratio: 1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .order-image-wrapper:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .order-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #map {
            height: 400px;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .captain-card {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            border: 2px solid #bae6fd;
        }

        .captain-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #0284c7;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.2);
        }

        .captain-details {
            flex: 1;
        }

        .captain-name {
            font-size: 20px;
            font-weight: 700;
            color: #0c4a6e;
            margin-bottom: 5px;
        }

        .captain-phone {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #0369a1;
            font-size: 16px;
        }

        .invoice-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 25px;
            border-radius: 12px;
            border: 2px solid #fbbf24;
        }

        .invoice-total {
            font-size: 32px;
            font-weight: 700;
            color: #92400e;
            margin-top: 10px;
        }

        .action-form {
            background: white;
            padding: 25px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            margin-bottom: 20px;
        }

        .action-form label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
            display: block;
        }

        .action-form select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .action-form select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .btn-modern {
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-primary-modern {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-success-modern {
            background: var(--success-gradient);
            color: white;
        }

        .rating-display {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 12px;
        }

        .rating-stars {
            font-size: 28px;
        }

        .no-data-message {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
        }

        .no-data-message i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .order-number {
                font-size: 24px;
                flex-direction: column;
                align-items: flex-start;
            }

            .captain-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endsection
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">الطلبات</a></li>
    <li class="breadcrumb-item active">تفاصيل الطلب #{{ $order->number }}</li>
@endsection

@section('section')

    {{-- Order Header --}}
    <div class="order-header">
        <div class="order-header-content">
            <div class="order-number">
                <span>طلب رقم #{{ $order->number }}</span>
                <span class="badge">{{ $order->orderStatus->CurrentName ?? 'قيد المعالجة' }}</span>
                
                {{-- Payment Status Badge --}}
                @if($order->payment_status == 'paid')
                    <span class="badge" style="background: rgba(16, 185, 129, 0.9);">
                        <i class="mdi mdi-check-circle"></i> مدفوع
                    </span>
                @elseif($order->payment_status == 'pending')
                    <span class="badge" style="background: rgba(251, 191, 36, 0.9);">
                        <i class="mdi mdi-clock-outline"></i> قيد الانتظار
                    </span>
                @elseif($order->payment_status == 'failed')
                    <span class="badge" style="background: rgba(239, 68, 68, 0.9);">
                        <i class="mdi mdi-close-circle"></i> فشل الدفع
                    </span>
                @else
                    <span class="badge" style="background: rgba(156, 163, 175, 0.9);">
                        <i class="mdi mdi-help-circle"></i> غير محدد
                    </span>
                @endif
                
                {{-- Generate Device Token Button --}}
                <button id="generateTokenBtn" class="badge" style="background: rgba(59, 130, 246, 0.9); cursor: pointer; border: none; transition: all 0.3s;">
                    <i class="mdi mdi-bell-ring"></i> <span id="tokenBtnText">تفعيل الإشعارات</span>
                </button>
            </div>
            <div class="customer-info">
                <div class="customer-avatar">
                    {{ mb_substr($order->user->name, 0, 1) }}
                </div>
                <div>
                    <div style="font-size: 20px; font-weight: 600;">{{ $order->user->name }}</div>
                    <div style="opacity: 0.9;">{{ $order->user->phone }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Status Alert --}}
    @if($order->payment_status == 'paid')
        <div class="alert alert-success" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; border-right: 4px solid #10b981; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="mdi mdi-check-circle-outline" style="font-size: 24px;"></i>
                <div>
                    <strong>تم الدفع بنجاح</strong>
                    <p style="margin: 0; font-size: 14px; opacity: 0.9;">تم استلام الدفع وتأكيد الطلب</p>
                </div>
            </div>
        </div>
    @elseif($order->payment_status == 'pending')
        <div class="alert alert-warning" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e; border-right: 4px solid #f59e0b; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="mdi mdi-clock-alert-outline" style="font-size: 24px;"></i>
                <div>
                    <strong>في انتظار الدفع</strong>
                    <p style="margin: 0; font-size: 14px; opacity: 0.9;">الطلب قيد الانتظار حتى يتم استكمال عملية الدفع</p>
                </div>
            </div>
        </div>
    @elseif($order->payment_status == 'failed')
        <div class="alert alert-danger" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #991b1b; border-right: 4px solid #ef4444; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="mdi mdi-alert-circle-outline" style="font-size: 24px;"></i>
                <div>
                    <strong>فشل الدفع</strong>
                    <p style="margin: 0; font-size: 14px; opacity: 0.9;">حدث خطأ أثناء عملية الدفع. يرجى المحاولة مرة أخرى</p>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        {{-- Vehicle Information --}}
        <div class="col-lg-6">
            <div class="modern-card">
                <div class="card-header-modern">
                    <i class="mdi mdi-car"></i>
                    <h5>معلومات السيارة</h5>
                </div>
                <div class="card-body-modern">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon primary">
                                <i class="mdi mdi-car-side"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">اسم السيارة</div>
                                <div class="info-value">{{ $order->car ? $order->car->current_name : 'غير محدد' }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon info">
                                <i class="mdi mdi-car-info"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">نوع السيارة</div>
                                <div class="info-value">{{ $order->car_model ?? 'غير محدد' }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon warning">
                                <i class="mdi mdi-numeric"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">رقم السيارة</div>
                                <div class="info-value">{{ $order->car_number ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Booking Information --}}
        <div class="col-lg-6">
            <div class="modern-card">
                <div class="card-header-modern">
                    <i class="mdi mdi-calendar-clock"></i>
                    <h5>معلومات الحجز</h5>
                </div>
                <div class="card-body-modern">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon success">
                                <i class="mdi mdi-calendar"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">تاريخ الحجز</div>
                                <div class="info-value">{{ $order->booking_date }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon info">
                                <i class="mdi mdi-clock-outline"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">وقت الحجز</div>
                                <div class="info-value">{{ $order->booking_time }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Service Information --}}
    <div class="modern-card">
        <div class="card-header-modern">
            <i class="mdi mdi-room-service"></i>
            <h5>تفاصيل الخدمة</h5>
        </div>
        <div class="card-body-modern">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon primary">
                        <i class="mdi mdi-package-variant"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">اسم الخدمة/الباقة</div>
                        <div class="info-value">
                            {{ $order->service?->getCurrentNameAttribute()
                                ?? $order->userPackage?->package?->getCurrentNameAttribute()
                                ?? 'غير محدد' }}
                        </div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon info">
                        <i class="mdi mdi-timer-outline"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">وقت الخدمة/الصلاحية</div>
                        <div class="info-value">
                            {{ $order->service?->duration 
                                ?? ($order->userPackage?->package?->validity_days ? $order->userPackage->package->validity_days . ' يوم' : 'غير محدد') }}
                        </div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon success">
                        <i class="mdi mdi-cash"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">سعر الخدمة/الباقة</div>
                        <div class="info-value">
                            {{ $order->service?->price 
                                ?? $order->userPackage?->package?->price 
                                ?? 'غير محدد' }} ريال
                        </div>
                    </div>
                </div>
            </div>

            {{-- Additional Services --}}
            @if($order->choices->count() > 0)
                <div style="margin-top: 25px;">
                    <h6 style="color: #6b7280; margin-bottom: 15px; font-weight: 600;">
                        <i class="mdi mdi-plus-circle-outline"></i> خدمات إضافية
                    </h6>
                    <div class="additional-services">
                        @foreach($order->choices as $choice)
                            <div class="service-chip">
                                <i class="mdi mdi-check-circle text-success"></i>
                                <span>{{ $choice->name }}</span>
                                <span class="price">{{ $choice->service_price }} ريال</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Order Images --}}
            @if($order->images->count() > 0)
                <div style="margin-top: 25px;">
                    <h6 style="color: #6b7280; margin-bottom: 15px; font-weight: 600;">
                        <i class="mdi mdi-image-multiple"></i> صور الطلب
                    </h6>
                    <div class="order-images">
                        @foreach($order->images as $image)
                            <div class="order-image-wrapper" onclick="window.open('{{ asset('storage/' . $image->image_path) }}', '_blank')">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Order Image">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Location --}}
    @if($order->latitude && $order->longitude)
        <div class="modern-card">
            <div class="card-header-modern">
                <i class="mdi mdi-map-marker"></i>
                <h5>موقع الخدمة</h5>
            </div>
            <div class="card-body-modern">
                <div class="info-item" style="margin-bottom: 20px;">
                    <div class="info-icon warning">
                        <i class="mdi mdi-map-marker-radius"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">العنوان</div>
                        <div class="info-value">{{ $order->address ?? 'غير محدد' }}</div>
                    </div>
                </div>
                <div id="map"></div>
            </div>
        </div>
    @endif

    {{-- Rating --}}
    @if($order->rating && $order->rating->stars)
        <div class="modern-card">
            <div class="card-header-modern">
                <i class="mdi mdi-star"></i>
                <h5>التقييم</h5>
            </div>
            <div class="card-body-modern">
                <div class="rating-display">
                    <div class="rating-stars">
                        <input type="hidden" value="{{ $order->rating->stars }}" class="rating"
                               data-filled="mdi mdi-star text-warning"
                               data-empty="mdi mdi-star-outline text-muted" data-readonly/>
                    </div>
                    @if($order->rating->comment)
                        <div>
                            <div class="info-label">التعليق</div>
                            <div class="info-value">{{ $order->rating->comment }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Captain Information --}}
    <div class="modern-card">
        <div class="card-header-modern">
            <i class="mdi mdi-account-tie"></i>
            <h5>معلومات الكابتن</h5>
        </div>
        <div class="card-body-modern">
            @if($order->captain)
                <div class="captain-card">
                    <div class="captain-avatar">
                        {{ mb_substr($order->captain->name, 0, 1) }}
                    </div>
                    <div class="captain-details">
                        <div class="captain-name">
                            <a href="{{ route('captains.show', $order->captain->id) }}" style="color: inherit; text-decoration: none;">
                                {{ $order->captain->name . ' ' . $order->captain->last_name }}
                            </a>
                        </div>
                        <div class="captain-phone">
                            <i class="mdi mdi-phone"></i>
                            <span>{{ $order->captain->phone_number }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="no-data-message">
                    <i class="mdi mdi-account-off-outline"></i>
                    <p>لا يوجد كابتن مُعيّن لهذا الطلب حالياً</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Invoice --}}
    <div class="modern-card">
        <div class="card-header-modern">
            <i class="mdi mdi-receipt"></i>
            <h5>الفاتورة</h5>
            {{-- Payment Status in Header --}}
            @if($order->payment_status == 'paid')
                <span style="margin-right: auto; padding: 6px 14px; background: #10b981; color: white; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    <i class="mdi mdi-check-circle"></i> مدفوع
                </span>
            @elseif($order->payment_status == 'pending')
                <span style="margin-right: auto; padding: 6px 14px; background: #f59e0b; color: white; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    <i class="mdi mdi-clock-outline"></i> قيد الانتظار
                </span>
            @elseif($order->payment_status == 'failed')
                <span style="margin-right: auto; padding: 6px 14px; background: #ef4444; color: white; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    <i class="mdi mdi-close-circle"></i> فشل
                </span>
            @endif
        </div>
        <div class="card-body-modern">
            <div class="invoice-section" style="background: {{ $order->payment_status == 'paid' ? 'linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%)' : ($order->payment_status == 'pending' ? 'linear-gradient(135deg, #fef3c7 0%, #fde68a 100%)' : 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)') }}; border-color: {{ $order->payment_status == 'paid' ? '#10b981' : ($order->payment_status == 'pending' ? '#fbbf24' : '#ef4444') }};">
                <div class="info-grid" style="margin-bottom: 20px;">
                    <div class="info-item" style="background: white;">
                        <div class="info-icon primary">
                            <i class="mdi mdi-account"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">اسم العميل</div>
                            <div class="info-value">{{ $order->user->name }}</div>
                        </div>
                    </div>

                    <div class="info-item" style="background: white;">
                        <div class="info-icon info">
                            <i class="mdi mdi-phone"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">رقم الجوال</div>
                            <div class="info-value">{{ $order->user->phone }}</div>
                        </div>
                    </div>

                    <div class="info-item" style="background: white;">
                        <div class="info-icon {{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                            <i class="mdi mdi-credit-card"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">طريقة الدفع</div>
                            <div class="info-value">
                                @if($order->payment_method == 'creditcard')
                                    بطاقة ائتمان
                                @elseif($order->payment_method == 'mada')
                                    مدى
                                @elseif($order->payment_method == 'applepay')
                                    Apple Pay
                                @elseif($order->payment_method == 'package')
                                    باقة مدفوعة
                                @else
                                    {{ $order->payment_method ?? 'غير محدد' }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div style="text-align: center; padding: 20px; background: white; border-radius: 12px;">
                    <div class="info-label">إجمالي التكلفة</div>
                    <div class="invoice-total" style="color: {{ $order->payment_status == 'paid' ? '#065f46' : ($order->payment_status == 'pending' ? '#92400e' : '#991b1b') }};">
                        {{ $order->total_price }} ريال
                    </div>
                    
                    {{-- Payment Status Text --}}
                    @if($order->payment_status == 'paid')
                        <div style="margin-top: 10px; color: #065f46; font-weight: 600;">
                            <i class="mdi mdi-check-decagram"></i> تم الدفع بنجاح
                        </div>
                    @elseif($order->payment_status == 'pending')
                        <div style="margin-top: 10px; color: #92400e; font-weight: 600;">
                            <i class="mdi mdi-timer-sand"></i> في انتظار الدفع
                        </div>
                    @elseif($order->payment_status == 'failed')
                        <div style="margin-top: 10px; color: #991b1b; font-weight: 600;">
                            <i class="mdi mdi-alert-octagon"></i> فشل الدفع
                        </div>
                    @endif
                </div>

                <div style="margin-top: 20px; text-align: center;">
                    @if($order->payment_status == 'paid')
                        @if($order->invoice_url)
                            <a href="{{ asset('storage/' . $order->invoice_url) }}" 
                               target="_blank" 
                               class="btn-modern btn-success-modern">
                                <i class="mdi mdi-file-pdf-box"></i>
                                تحميل الفاتورة
                            </a>
                        @else
                            <span class="text-muted">لم يتم إنشاء الفاتورة بعد</span>
                        @endif
                    @elseif($order->payment_status == 'pending')
                        <div style="padding: 15px; background: white; border-radius: 10px; color: #92400e;">
                            <i class="mdi mdi-information-outline"></i>
                            سيتم إنشاء الفاتورة بعد إتمام عملية الدفع
                        </div>
                    @elseif($order->payment_status == 'failed')
                        <div style="padding: 15px; background: white; border-radius: 10px; color: #991b1b;">
                            <i class="mdi mdi-alert-circle-outline"></i>
                            يرجى إعادة محاولة الدفع لإنشاء الفاتورة
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Assign Captain Form - Only show if paid --}}
    @if($order->payment_status == 'paid')
        <div class="action-form" style="border-color: #10b981; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                <i class="mdi mdi-check-circle" style="color: #10b981; font-size: 24px;"></i>
                <div>
                    <h6 style="margin: 0; color: #065f46; font-weight: 700;">الطلب مدفوع</h6>
                    <p style="margin: 0; font-size: 13px; color: #047857;">يمكنك الآن تعيين كابتن لهذا الطلب</p>
                </div
            </div>
            <form action="{{ route('orders.assignCaptain', $order->id) }}" method="post">
                @csrf
                @method('put')
                <label for="captain_select">
                    <i class="mdi mdi-account-plus"></i> تعيين كابتن للطلب
                </label>
                <select class="form-select" name="captain_id" id="captain_select" required style="border-color: #10b981;">
                    <option value="">اختر كابتن...</option>
                    @forelse($availableCaptains as $captain)
                        <option value="{{ $captain->id }}" @selected($order->captain_id == $captain->id)>
                            {{ $captain->name . ' ' . $captain->last_name }}
                        </option>
                    @empty
                        <option disabled>لا يوجد كابتن متاح</option>
                    @endforelse
                </select>
                <button class="btn-modern btn-success-modern mt-3" type="submit">
                    <i class="mdi mdi-check"></i>
                    تعيين الكابتن
                </button>
            </form>
        </div>
    @else
        <div class="action-form" style="border-color: #f59e0b; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="mdi mdi-lock-outline" style="color: #f59e0b; font-size: 32px;"></i>
                <div>
                    <h6 style="margin: 0; color: #92400e; font-weight: 700;">
                        @if($order->payment_status == 'pending')
                            في انتظار الدفع
                        @elseif($order->payment_status == 'failed')
                            فشل الدفع
                        @else
                            الطلب غير مدفوع
                        @endif
                    </h6>
                    <p style="margin: 0; font-size: 13px; color: #b45309;">
                        @if($order->payment_status == 'pending')
                            لا يمكن تعيين كابتن حتى يتم إتمام عملية الدفع
                        @elseif($order->payment_status == 'failed')
                            يرجى إعادة محاولة الدفع لتتمكن من تعيين كابتن
                        @else
                            يجب إتمام عملية الدفع أولاً لتعيين كابتن
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Update Order Status Form --}}
    <div class="action-form">
        <form action="{{ route('orders.update', $order->id) }}" method="post">
            @csrf
            @method('put')
            <label for="status_select">
                <i class="mdi mdi-update"></i> تغيير حالة الطلب
            </label>
            <select class="form-select" name="order_status_id" id="status_select" required>
                @forelse($orderStatus as $status)
                    <option value="{{ $status->id }}" @selected($order->order_status_id == $status->id)>
                        {{ $status->CurrentName }}
                    </option>
                @empty
                    <option disabled>لا توجد حالات متاحة</option>
                @endforelse
            </select>
            <button class="btn-modern btn-primary-modern mt-3" type="submit">
                <i class="mdi mdi-content-save"></i>
                حفظ الحالة
            </button>
        </form>
    </div>

    {{-- Google Maps Script --}}
    @if($order->latitude && $order->longitude)
        <script>
            function initMap() {
                var location = {lat: {{ $order->latitude }}, lng: {{ $order->longitude }}};
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: location,
                    styles: [
                        {
                            "featureType": "all",
                            "elementType": "geometry",
                            "stylers": [{"color": "#f5f5f5"}]
                        },
                        {
                            "featureType": "water",
                            "elementType": "geometry",
                            "stylers": [{"color": "#e9e9e9"}]
                        }
                    ]
                });

                var marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: 'موقع الطلب',
                    animation: google.maps.Animation.DROP
                });
            }
        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuK5dAqp6_Fs7d58Qs-5SvJeyHECUJAoM&callback=initMap" async defer></script>
    @endif

    {{-- Firebase Notification Script --}}
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyC3tzyv__3udwxPWI5swk12qoGZ4J5sb1c",
            authDomain: "test-notification-3f882.firebaseapp.com",
            projectId: "test-notification-3f882",
            storageBucket: "test-notification-3f882.firebasestorage.app",
            messagingSenderId: "786873585382",
            appId: "1:786873585382:web:fe4db5ece2173b8eaaf527",
            measurementId: "G-31WKBC1QJW"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        // Save token to backend
        function saveTokenToBackend(token) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route("device-tokens.store") }}',
                type: 'POST',
                data: {
                    token: token,
                    device_type: 'web'
                },
                dataType: 'JSON',
                success: function (response) {
                    console.log('✅ Token saved successfully:', response);
                    
                    // Save status to localStorage
                    localStorage.setItem('firebase_token_status', 'active');
                    
                    // Update button UI
                    $('#tokenBtnText').text('الإشعارات مفعلة');
                    $('#generateTokenBtn').css('background', 'rgba(16, 185, 129, 0.9)');
                    
                    // Show success notification
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم بنجاح',
                            text: 'تم تفعيل الإشعارات بنجاح',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        alert('تم تفعيل الإشعارات بنجاح');
                    }
                },
                error: function (err) {
                    console.error('❌ Error saving token:', err);
                    
                    // Update button UI
                    $('#tokenBtnText').text('فشل التفعيل');
                    $('#generateTokenBtn').css('background', 'rgba(239, 68, 68, 0.9)');
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: 'فشل حفظ رمز الإشعارات',
                            timer: 2000
                        });
                    } else {
                        alert('فشل حفظ رمز الإشعارات');
                    }
                }
            });
        }

        // Initialize Firebase Messaging Registration (Auto)
        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function () {
                    console.log('✅ Notification permission granted');
                    return messaging.getToken();
                })
                .then(function (token) {
                    console.log('🔑 Firebase Token:', token);
                    saveTokenToBackend(token);
                })
                .catch(function (err) {
                    console.error('❌ Firebase permission error:', err);
                    $('#tokenBtnText').text('تم الرفض');
                    $('#generateTokenBtn').css('background', 'rgba(239, 68, 68, 0.9)');
                });
        }

        // Manual Token Generation (Button Click)
        function generateDeviceToken() {
            const btn = $('#generateTokenBtn');
            const btnText = $('#tokenBtnText');
            
            // Update button to loading state
            btnText.text('جاري التفعيل...');
            btn.css('background', 'rgba(251, 191, 36, 0.9)');
            btn.prop('disabled', true);
            
            messaging
                .requestPermission()
                .then(function () {
                    console.log('✅ Notification permission granted');
                    return messaging.getToken();
                })
                .then(function (token) {
                    console.log('🔑 Firebase Token Generated:', token);
                    console.log('📋 Token copied to console for testing');
                    
                    // Save to backend
                    saveTokenToBackend(token);
                    
                    // Re-enable button
                    btn.prop('disabled', false);
                })
                .catch(function (err) {
                    console.error('❌ Error generating token:', err);
                    
                    // Reset button
                    btnText.text('فشل التفعيل');
                    btn.css('background', 'rgba(239, 68, 68, 0.9)');
                    btn.prop('disabled', false);
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: 'يرجى السماح بالإشعارات من إعدادات المتصفح',
                            confirmButtonText: 'حسناً'
                        });
                    } else {
                        alert('يرجى السماح بالإشعارات من إعدادات المتصفح');
                    }
                });
        }

        // Handle foreground messages
        messaging.onMessage(function (payload) {
            console.log('📬 Message received:', payload);
            
            const noteTitle = payload.notification.title;
            const noteOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon || '/favicon.ico',
            };
            
            // Show browser notification
            new Notification(noteTitle, noteOptions);
            
            // Show in-page notification if SweetAlert is available
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: noteTitle,
                    text: payload.notification.body,
                    timer: 5000,
                    showConfirmButton: true
                });
            }
        });

        // Check if token already exists on page load
        $(document).ready(function() {
            // Check localStorage for token status
            const tokenStatus = localStorage.getItem('firebase_token_status');
            
            if (tokenStatus === 'active') {
                $('#tokenBtnText').text('الإشعارات مفعلة');
                $('#generateTokenBtn').css('background', 'rgba(16, 185, 129, 0.9)');
            }
            
            // Bind click event to generate token button
            $('#generateTokenBtn').on('click', function() {
                generateDeviceToken();
            });
        });
    </script>
@endsection

@push('scripts')
    <!-- Bootstrap rating js -->
    <script src="{{ asset('assets/libs/bootstrap-rating/bootstrap-rating.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/rating-init.js') }}"></script>
@endpush