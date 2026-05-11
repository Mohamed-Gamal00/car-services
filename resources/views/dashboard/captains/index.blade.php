@extends('dashboard.index')
@section('title', 'الموظفين')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">الموظفين</li>
@endsection

@section('section')
    <style>
        .captains-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .captains-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 12px;
            overflow: hidden;
        }
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-card.success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .stat-card.warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-info h6 {
            margin: 0 0 5px 0;
            opacity: 0.9;
            font-size: 0.85rem;
        }
        .stat-info h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.8rem;
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
        }
        .btn-add-captain {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        .btn-add-captain:hover {
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
        .captain-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .captain-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .captain-name {
            font-weight: 700;
            color: #2c3e50;
        }
        .phone-badge {
            background: #e7f3ff;
            color: #0066cc;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
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
        .status-available { background: #d4edda; color: #155724; }
        .status-busy { background: #fff3cd; color: #856404; }
        .status-active { background: #d4edda; color: #155724; }
        .status-inactive { background: #f8d7da; color: #721c24; }
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
            color: white;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .btn-edit { background: #667eea; }
        .btn-view { background: #28a745; }
        .btn-rating { background: #ffc107; }
    </style>

    <div class="captains-container">
        <!-- Statistics Cards -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-info">
                    <h6>إجمالي الموظفين</h6>
                    <h3>{{ $captains->total() }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="mdi mdi-account-group"></i>
                </div>
            </div>
            <div class="stat-card success">
                <div class="stat-info">
                    <h6>المتاحون</h6>
                    <h3>{{ $captains->where('status', 'available')->count() }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="mdi mdi-check-circle"></i>
                </div>
            </div>
            <div class="stat-card warning">
                <div class="stat-info">
                    <h6>المشغولون</h6>
                    <h3>{{ $captains->where('status', 'busy')->count() }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="mdi mdi-clock-alert"></i>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card captains-card">
            <div class="card-body p-4">
                <x-alert type='success'/>
                <x-alert type='dark'/>
                <x-alert type='danger'/>

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-1" style="color: #2c3e50; font-weight: 700;">
                            <i class="mdi mdi-account-tie me-2"></i>
                            قائمة الموظفين
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">
                            إدارة وعرض جميع الموظفين المسجلين
                        </p>
                    </div>
                    <a href="{{ route('captains.create') }}" class="btn btn-add-captain">
                        <i class="mdi mdi-plus-circle me-2"></i>
                        إضافة موظف جديد
                    </a>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th><i class="mdi mdi-account me-1"></i>الموظف</th>
                                <th><i class="mdi mdi-phone me-1"></i>رقم الجوال</th>
                                <th><i class="mdi mdi-toggle-switch me-1"></i>حالة العضوية</th>
                                <th><i class="mdi mdi-briefcase me-1"></i>حالة العمل</th>
                                <th><i class="mdi mdi-calendar me-1"></i>تاريخ الإنشاء</th>
                                <th class="text-center"><i class="mdi mdi-cog me-1"></i>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($captains as $captain)
                                <tr>
                                    <td>
                                        <div class="captain-info">
                                            <div class="captain-avatar">
                                                {{ mb_substr($captain->name, 0, 1) }}
                                            </div>
                                            <div class="captain-name">
                                                {{ $captain->name . ' ' . $captain->last_name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="phone-badge">
                                            <i class="mdi mdi-cellphone"></i>
                                            {{ $captain->phone }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($captain->status == 'available')
                                            <span class="status-badge status-available">
                                                <i class="mdi mdi-check-circle"></i>
                                                متاح
                                            </span>
                                        @else
                                            <span class="status-badge status-busy">
                                                <i class="mdi mdi-clock-outline"></i>
                                                مشغول
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($captain->is_active == 1)
                                            <span class="status-badge status-active">
                                                <i class="mdi mdi-check"></i>
                                                نشط
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <i class="mdi mdi-close"></i>
                                                غير نشط
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            {{ $captain->created_at->format('Y-m-d H:i') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('captains.edit', $captain->id) }}" 
                                               class="action-btn btn-edit" 
                                               title="تعديل">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <a href="{{ route('captains.show', $captain->id) }}" 
                                               class="action-btn btn-view" 
                                               title="مشاهدة">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('captain.rating', $captain->id) }}" 
                                               class="action-btn btn-rating" 
                                               title="التقييم">
                                                <i class="mdi mdi-star"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="text-center py-5">
                                            <i class="mdi mdi-account-off-outline" style="font-size: 4rem; color: #dee2e6;"></i>
                                            <h5 class="text-muted mt-3">لا يوجد موظفين</h5>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $captains->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
