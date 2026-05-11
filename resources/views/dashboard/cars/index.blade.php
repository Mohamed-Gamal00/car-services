@extends('dashboard.index')
@section('title', 'السيارات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">السيارات</li>
@endsection

@section('section')
    <style>
        .cars-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .cars-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
        }
        .cars-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
        }
        .stats-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stats-info h6 {
            margin: 0 0 5px 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }
        .stats-info h2 {
            margin: 0;
            font-weight: 700;
            font-size: 2rem;
        }
        .stats-icon {
            font-size: 3.5rem;
            opacity: 0.3;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .btn-add-car {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        .btn-add-car:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .table-modern {
            border-collapse: separate;
            border-spacing: 0 8px;
        }
        .table-modern thead th {
            background: #f8f9fa;
            border: none;
            padding: 15px;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        .table-modern tbody tr {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        .table-modern tbody tr:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transform: scale(1.01);
        }
        .table-modern tbody td {
            padding: 15px;
            vertical-align: middle;
            border: none;
        }
        .car-brand {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1rem;
        }
        .car-model {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .language-badge {
            background: #e9ecef;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
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
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            border: none;
            margin: 0 2px;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .btn-edit {
            background: #667eea;
            color: white;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        .car-info-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .car-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
        }
        .year-badge {
            background: #fff3cd;
            color: #856404;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .color-indicator {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .color-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #dee2e6;
        }
    </style>

    <div class="cars-container">
        <!-- Statistics Card -->
        <div class="stats-card">
            <div class="stats-content">
                <div class="stats-info">
                    <h6>إجمالي السيارات المسجلة</h6>
                    <h2>{{ $cars->total() }}</h2>
                    <small style="opacity: 0.8;">
                        عرض {{ $cars->count() }} من {{ $cars->total() }} سيارة
                    </small>
                </div>
                <div class="stats-icon">
                    <i class="mdi mdi-car-multiple"></i>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card cars-card">
            <div class="card-body p-4">
                <x-alert type='success'/>
                <x-alert type='danger'/>
                <x-alert type='dark'/>

                <!-- Header Section -->
                <div class="header-section">
                    <div>
                        <h5 class="mb-1" style="color: #2c3e50; font-weight: 700;">
                            <i class="mdi mdi-car-side me-2"></i>
                            قائمة السيارات
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">
                            إدارة وعرض جميع السيارات المسجلة في النظام
                        </p>
                    </div>
                    @can('country.create')
                        <a href="{{ route('cars.create') }}" class="btn btn-add-car">
                            <i class="mdi mdi-plus-circle me-2"></i>
                            إضافة سيارة جديدة
                        </a>
                    @endcan
                </div>

                <!-- Table Section -->
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th><i class="mdi mdi-car-info me-1"></i>معلومات السيارة</th>
                                <th><i class="mdi mdi-calendar me-1"></i>السنة</th>
                                <th><i class="mdi mdi-palette me-1"></i>اللون</th>
                                <th><i class="mdi mdi-toggle-switch me-1"></i>الحالة</th>
                                <th class="text-center"><i class="mdi mdi-cog me-1"></i>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cars as $car)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-muted">{{ $loop->iteration }}</div>
                                    </td>
                                    <td>
                                        <div class="car-info-cell">
                                            <div class="car-icon">
                                                <i class="mdi mdi-car"></i>
                                            </div>
                                            <div>
                                                <div class="car-brand">
                                                    <span class="language-badge ar">ع</span>
                                                    {{ $car->brand_ar }}
                                                    @if($car->brand_en)
                                                        <span class="mx-1">|</span>
                                                        <span class="language-badge en">EN</span>
                                                        {{ $car->brand_en }}
                                                    @endif
                                                </div>
                                                <div class="car-model">
                                                    <i class="mdi mdi-chevron-left" style="font-size: 0.8rem;"></i>
                                                    {{ $car->model_ar }}
                                                    @if($car->model_en)
                                                        <span class="mx-1">|</span>
                                                        {{ $car->model_en }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($car->year)
                                            <span class="year-badge">
                                                <i class="mdi mdi-calendar-blank"></i>
                                                {{ $car->year }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($car->color)
                                            <div class="color-indicator">
                                                <i class="mdi mdi-palette text-primary"></i>
                                                <span>{{ $car->color }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($car->is_active)
                                            <span class="status-badge status-active">
                                                <i class="mdi mdi-check-circle"></i>
                                                نشط
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <i class="mdi mdi-close-circle"></i>
                                                غير نشط
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            @can('car.edit')
                                                <a href="{{ route('cars.edit', $car->id) }}" 
                                                   class="action-btn btn-edit" 
                                                   title="تعديل">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                            @endcan
                                            @can('car.delete')
                                                <form method="post" 
                                                      id="formDelete_{{ $car->id }}"
                                                      action="{{ route('cars.destroy', $car->id) }}" 
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="action-btn btn-delete" 
                                                            title="حذف"
                                                            type="button" 
                                                            onclick="confirmDelete({{ $car->id }})">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="mdi mdi-car-off"></i>
                                            <h5 class="text-muted">لا توجد سيارات</h5>
                                            <p class="text-muted">لم يتم العثور على أي سيارات في الوقت الحالي</p>
                                            @can('country.create')
                                                <a href="{{ route('cars.create') }}" class="btn btn-add-car mt-3">
                                                    <i class="mdi mdi-plus-circle me-2"></i>
                                                    إضافة سيارة جديدة
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $cars->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection