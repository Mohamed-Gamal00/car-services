@extends('dashboard.index')
@section('title', ' البيانات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('captains.index')}}">الموظفين</a></li>
    <li class="breadcrumb-item"> بيانات كابتن</li>
@endsection

@section('section')
    <style>
        .profile-card { border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 16px; overflow: hidden; }
        .profile-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 20px 80px; position: relative; }
        .profile-avatar { width: 120px; height: 120px; border: 5px solid white; border-radius: 50%; margin: 0 auto; display: block; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .profile-name { font-size: 1.5rem; font-weight: 700; color: #2c3e50; margin-top: 15px; }
        .profile-status { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 0.9rem; font-weight: 600; }
        .status-available { background: #d4edda; color: #155724; }
        .status-busy { background: #f8d7da; color: #721c24; }
        .profile-stats { border-top: 2px solid #f0f0f0; padding-top: 20px; margin-top: 20px; }
        .stat-item { text-align: center; }
        .stat-value { font-size: 1.8rem; font-weight: 700; color: #667eea; margin-bottom: 5px; }
        .stat-label { color: #6c757d; font-size: 0.9rem; }
        .section-card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-radius: 12px; margin-bottom: 25px; }
        .section-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 20px; border-radius: 12px 12px 0 0; display: flex; align-items: center; gap: 10px; }
        .section-header i { font-size: 1.5rem; }
        .section-header h5 { margin: 0; font-weight: 600; }
        .table-modern { margin-bottom: 0; }
        .table-modern thead th { background: #f8f9fa; color: #495057; font-weight: 600; border: none; padding: 15px; }
        .table-modern tbody tr { transition: all 0.3s; border-bottom: 1px solid #f0f0f0; }
        .table-modern tbody tr:hover { background: #f8f9fa; transform: translateX(-3px); }
        .table-modern tbody td { padding: 15px; vertical-align: middle; border: none; }
        .btn-view { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 8px 20px; border-radius: 20px; font-size: 0.85rem; transition: all 0.3s; }
        .btn-view:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); color: white; }
        .status-badge { padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; }
        .status-badge i { font-size: 1rem; }
        .empty-state { text-align: center; padding: 40px 20px; color: #6c757d; }
        .empty-state i { font-size: 3rem; opacity: 0.3; margin-bottom: 15px; }
        .current-order-card { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 12px; padding: 20px; }
        .avatar-circle { width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: inline-flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem; }
    </style>

    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-xl-4 col-lg-5">
            <div class="card profile-card sticky-top" style="top: 20px;">
                <div class="profile-header"></div>
                <div class="card-body text-center" style="margin-top: -60px;">
                    <img src="{{ $captain->image_url }}" alt="{{$captain->name}}" class="profile-avatar">
                    
                    <h4 class="profile-name">{{$captain->name}} {{$captain->last_name}}</h4>
                    
                    <span class="profile-status {{ $captain->status == 'available' ? 'status-available' : 'status-busy' }}">
                        <i class="mdi mdi-{{ $captain->status == 'available' ? 'check-circle' : 'clock-alert' }}"></i>
                        {{$captain->status == 'available' ? 'متاح' : 'مشغول'}}
                    </span>

                    <div class="profile-stats">
                        <div class="row">
                            <div class="col-6 stat-item">
                                <div class="stat-value">{{count($completed_orders)}}</div>
                                <div class="stat-label">الطلبات المكتملة</div>
                            </div>
                            <div class="col-6 stat-item">
                                <div class="stat-value">
                                    <i class="mdi mdi-phone" style="font-size: 1.2rem;"></i>
                                </div>
                                <div class="stat-label">{{$captain->phone_number}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-xl-8 col-lg-7">
            <!-- Completed Orders -->
            <div class="card section-card">
                <div class="section-header">
                    <i class="mdi mdi-check-circle"></i>
                    <h5>الطلبات المكتملة</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>اسم العميل</th>
                                    <th>تاريخ الطلب</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($completed_orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->number }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-circle">{{ substr($order->user->first_name, 0, 1) }}</div>
                                                {{ $order->user->first_name }}
                                            </div>
                                        </td>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-view">
                                                <i class="mdi mdi-eye"></i> مشاهدة
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state">
                                                <i class="mdi mdi-package-variant"></i>
                                                <p class="mb-0">لا يوجد طلبات مكتملة</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($completed_orders->hasPages())
                    <div class="card-footer bg-white border-top-0">
                        {{ $completed_orders->withQueryString()->links() }}
                    </div>
                @endif
            </div>

            <!-- Current Order -->
            <div class="card section-card">
                <div class="section-header">
                    <i class="mdi mdi-truck-fast"></i>
                    <h5>الطلب الحالي</h5>
                </div>
                <div class="card-body">
                    @if($current_order)
                        <div class="current-order-card">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="mdi mdi-package-variant" style="font-size: 3rem; color: #667eea;"></i>
                                        <h5 class="mt-2 mb-0">#{{ $current_order->number }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <p class="mb-2"><strong>العميل:</strong> {{ $current_order->user->first_name }}</p>
                                        <p class="mb-0"><strong>التاريخ:</strong> {{ $current_order->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <a href="{{ route('orders.show', $current_order->id) }}" class="btn btn-view">
                                        <i class="mdi mdi-eye"></i> مشاهدة
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="mdi mdi-truck-fast"></i>
                            <p class="mb-0">لا يوجد طلب حالي</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Waiting List -->
            <div class="card section-card">
                <div class="section-header">
                    <i class="mdi mdi-clock-outline"></i>
                    <h5>قائمة الانتظار</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>اسم العميل</th>
                                    <th>تاريخ الطلب</th>
                                    <th>الحالة</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($wating_list as $order)
                                    <tr>
                                        <td><strong>#{{ $order->number }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-circle">{{ substr($order->user->first_name, 0, 1) }}</div>
                                                {{ $order->user->first_name }}
                                            </div>
                                        </td>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            @if ($order->order_status_id == 4)
                                                <span class="status-badge" style="background: #d4edda; color: #155724;">
                                                    <i class="mdi mdi-check-circle"></i>
                                                    {{ $order->orderStatus->name }}
                                                </span>
                                            @elseif($order->order_status_id == 2)
                                                <span class="status-badge" style="background: #fff3cd; color: #856404;">
                                                    <i class="mdi mdi-clock-alert"></i>
                                                    {{ $order->orderStatus->name }}
                                                </span>
                                            @elseif($order->order_status_id == 3)
                                                <span class="status-badge" style="background: #e2d5f5; color: #5a2d82;">
                                                    <i class="mdi mdi-progress-clock"></i>
                                                    {{ $order->orderStatus->name }}
                                                </span>
                                            @elseif($order->order_status_id == 11)
                                                <span class="status-badge" style="background: #f8d7da; color: #721c24;">
                                                    <i class="mdi mdi-close-circle"></i>
                                                    {{ $order->orderStatus->name }}
                                                </span>
                                            @elseif($order->order_status_id == 12)
                                                <span class="status-badge" style="background: #cfe2ff; color: #084298;">
                                                    <i class="mdi mdi-information"></i>
                                                    {{ $order->orderStatus->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">---</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-view">
                                                <i class="mdi mdi-eye"></i> مشاهدة
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <i class="mdi mdi-clock-outline"></i>
                                                <p class="mb-0">لا يوجد طلبات في قائمة الانتظار</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($wating_list->hasPages())
                    <div class="card-footer bg-white border-top-0">
                        {{ $wating_list->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection