@extends('dashboard.index')
@section('title', 'طلبات العميل')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('clients.index')}}">العملاء</a></li>
    <li class="breadcrumb-item">طلبات العميل {{$client->first_name}}</li>
@endsection

@section('section')
    <style>
        .client-profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            border: 3px solid rgba(255,255,255,0.3);
        }
        .profile-info {
            flex: 1;
        }
        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .profile-detail {
            opacity: 0.9;
            font-size: 0.95rem;
        }
        .stats-mini-card {
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        .stats-mini-card h3 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
        }
        .stats-mini-card p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 0.875rem;
        }
        .order-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .order-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
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
        .order-number {
            font-weight: 700;
            color: #667eea;
            font-size: 1.1rem;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .payment-status {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
        }
        .payment-paid {
            background: #d4edda;
            color: #155724;
        }
        .payment-pending {
            background: #fff3cd;
            color: #856404;
        }
        .payment-failed {
            background: #f8d7da;
            color: #721c24;
        }
        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            border: none;
            background: #667eea;
            color: white;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            background: #5568d3;
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
        .date-display {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .admin-badge {
            background: #17a2b8;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            margin-right: 5px;
        }
    </style>

    <!-- Client Profile Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card client-profile-card">
                <div class="card-body">
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="profile-avatar">
                            {{ mb_substr($client->first_name, 0, 1) }}
                        </div>
                        <div class="profile-info ms-4 mb-3 mb-md-0">
                            <div class="profile-name">{{ $client->first_name . ' ' . $client->family_name }}</div>
                            <div class="profile-detail">
                                <i class="mdi mdi-phone me-1"></i>
                                {{ $client->phone_number }}
                            </div>
                            <div class="profile-detail">
                                <i class="mdi mdi-calendar me-1"></i>
                                عضو منذ {{ $client->created_at->format('Y-m-d') }}
                            </div>
                        </div>
                        <div class="ms-auto d-flex gap-3">
                            <div class="stats-mini-card">
                                <h3>{{ $client->orders->count() }}</h3>
                                <p>إجمالي الطلبات</p>
                            </div>
                            <div class="stats-mini-card">
                                <h3>{{ $client->orders->where('payment_status', 'paid')->count() }}</h3>
                                <p>طلبات مدفوعة</p>
                            </div>
                            <div class="stats-mini-card">
                                <h3>{{ $client->orders->where('order_status_id', 4)->count() }}</h3>
                                <p>طلبات مكتملة</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card order-card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="mdi mdi-cart-outline me-2"></i>
                        طلبات العميل
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th><i class="mdi mdi-file-document-outline me-1"></i>رقم الطلب</th>
                                    <th><i class="mdi mdi-account me-1"></i>اسم العميل</th>
                                    <th><i class="mdi mdi-calendar me-1"></i>تاريخ الطلب</th>
                                    <th><i class="mdi mdi-information me-1"></i>حالة الطلب</th>
                                    <th><i class="mdi mdi-cash me-1"></i>حالة الدفع</th>
                                    <th class="text-center"><i class="mdi mdi-eye me-1"></i>التفاصيل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($client->orders as $order)
                                    <tr>
                                        <td>
                                            <span class="order-number">#{{ $order->number }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $order->user->first_name. ' ' .$order->user->family_name }}</div>
                                        </td>
                                        <td>
                                            <div class="date-display">
                                                <i class="mdi mdi-clock-outline me-1"></i>
                                                {{ $order->created_at->format('Y-m-d H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($order->order_status_id == 4)
                                                <span class="status-badge" style="background: #d4edda; color: #155724;">
                                                    <i class="mdi mdi-check-circle"></i>
                                                    {{ $order->orderStatus->name }}
                                                </span>
                                            @elseif($order->order_status_id == 2)
                                                <span class="status-badge" style="background: #fff3cd; color: #856404;">
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    {{ $order->orderStatus->name }}
                                                </span>
                                            @elseif($order->order_status_id == 3)
                                                <span class="status-badge" style="background: #e2d5f5; color: #6f42c1;">
                                                    <i class="mdi mdi-truck-delivery"></i>
                                                    {{ $order->orderStatus->name }}
                                                </span>
                                            @elseif($order->order_status_id == 11)
                                                <span class="status-badge" style="background: #f8d7da; color: #721c24;">
                                                    <i class="mdi mdi-close-circle"></i>
                                                    {{ $order->orderStatus->name }}
                                                </span>
                                            @elseif($order->order_status_id == 12)
                                                <span class="status-badge" style="background: #d1ecf1; color: #0c5460;">
                                                    <i class="mdi mdi-information"></i>
                                                    {{ $order->orderStatus->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">---</span>
                                            @endif
                                            @if ($order->updated_by_admin)
                                                <span class="admin-badge">مسئول</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->payment_status == 'paid')
                                                <span class="payment-status payment-paid">
                                                    <i class="mdi mdi-check-circle"></i>
                                                    مدفوع
                                                </span>
                                            @elseif($order->payment_status == 'pending')
                                                <span class="payment-status payment-pending">
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    معلق
                                                </span>
                                            @elseif($order->payment_status == 'failed')
                                                <span class="payment-status payment-failed">
                                                    <i class="mdi mdi-close-circle"></i>
                                                    فاشل
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('orders.show', $order->id) }}" 
                                               class="action-btn" 
                                               title="عرض التفاصيل">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <i class="mdi mdi-cart-off"></i>
                                                <h5 class="text-muted">لا توجد طلبات</h5>
                                                <p class="text-muted">لم يقم هذا العميل بإنشاء أي طلبات بعد</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($client->orders->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $client->orders->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
