@extends('dashboard.index')
@section('title', 'الطلبات')
@push('styles')
    <!-- Bootstrap datatable js -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
        }

        .orders-header {
            background: var(--primary-gradient);
            border-radius: 16px;
            padding: 25px 30px;
            color: white;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .orders-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .orders-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 12px;
        }

        .stat-card.total .stat-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-card.paid .stat-icon {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .stat-card.pending .stat-icon {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        .stat-card.failed .stat-icon {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
        }

        .stat-label {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
        }

        .modern-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card-header-modern {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px 25px;
            border-bottom: 2px solid #e9ecef;
        }

        .search-filters {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 15px;
            padding: 20px 25px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .search-filters input,
        .search-filters select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-filters input:focus,
        .search-filters select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .btn-search {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .modern-table thead th {
            background: #f9fafb;
            color: #374151;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            padding: 16px 20px;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f3f4f6;
        }

        .modern-table tbody tr:hover {
            background: #f9fafb;
        }

        .modern-table tbody td {
            padding: 16px 20px;
            color: #1f2937;
            font-size: 14px;
            vertical-align: middle;
        }

        .order-number {
            font-weight: 700;
            color: #667eea;
            font-size: 15px;
        }

        .customer-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }

        .customer-info {
            display: flex;
            flex-direction: column;
        }

        .customer-name {
            font-weight: 600;
            color: #1f2937;
            text-decoration: none;
            transition: color 0.2s;
        }

        .customer-name:hover {
            color: #667eea;
        }

        .customer-phone {
            font-size: 12px;
            color: #6b7280;
        }

        .service-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #f0f4ff;
            border-radius: 8px;
            font-size: 13px;
            color: #4338ca;
            font-weight: 600;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-badge.completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.processing {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.pending {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-badge.assigned {
            background: #e0e7ff;
            color: #4338ca;
        }

        .payment-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .payment-badge.paid {
            background: #d1fae5;
            color: #065f46;
        }

        .payment-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .payment-badge.failed {
            background: #fee2e2;
            color: #991b1b;
        }

        .price-cell {
            font-weight: 700;
            color: #059669;
            font-size: 15px;
        }

        .date-cell {
            color: #6b7280;
            font-size: 13px;
        }

        .actions-cell {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-action.view {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-action.view:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-2px);
        }

        .btn-action.delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-action.delete:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h5 {
            color: #6b7280;
            margin-bottom: 10px;
        }

        .pagination {
            padding: 20px 25px;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .search-filters {
                grid-template-columns: 1fr;
            }

            .orders-stats {
                grid-template-columns: 1fr;
            }

            .customer-cell {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">الطلبات</li>
@endsection

@section('section')
    {{-- Alerts --}}
    <x-alert type='success'/>
    <x-alert type='danger'/>
    <x-alert type='dark'/>

    {{-- Main Card --}}
    <div class="modern-card">
        {{-- Card Header --}}
        <div class="card-header-modern">
            <h4 style="margin: 0; font-weight: 700; display: flex; align-items: center; gap: 12px;">
                <i class="mdi mdi-cart-outline"></i>
                إدارة الطلبات
            </h4>
        </div>

        {{-- Search Filters --}}
        <form action="{{ URL::current() }}" method="get" class="search-filters">
            <input type="text" 
                   name="order_number" 
                   placeholder="🔍 البحث عن طريق رقم الطلب..."
                   value="{{ request('order_number') }}"
                   class="form-control">

            <select name="order_status_id" class="form-control">
                <option value="">كل الحالات</option>
                @forelse($OrderStatus as $status)
                    <option value="{{ $status->id }}" @selected(request('order_status_id') == $status->id)>
                        {{ $status->name }}
                    </option>
                @empty
                    <option disabled>لا توجد حالة</option>
                @endforelse
            </select>

            <button type="submit" class="btn-search">
                <i class="mdi mdi-magnify"></i>
                بحث
            </button>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>الخدمة</th>
                        <th>تاريخ الحجز</th>
                        <th>المبلغ</th>
                        <th>حالة الطلب</th>
                        <th>حالة الدفع</th>
                        <th>الكابتن</th>
                        <th style="text-align: center;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            {{-- Order Number --}}
                            <td>
                                <span class="order-number">#{{ $order->number }}</span>
                            </td>

                            {{-- Customer --}}
                            <td>
                                @if($order->user)
                                    <div class="customer-cell">
                                        <div class="customer-avatar">
                                            {{ mb_substr($order->user->name, 0, 1) }}
                                        </div>
                                        <div class="customer-info">
                                            <a href="{{ route('clients.edit', $order->user->id) }}" class="customer-name">
                                                {{ $order->user->name }}
                                            </a>
                                            <span class="customer-phone">{{ $order->user->phone }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Service --}}
                            <td>
                                <div class="service-badge">
                                    <i class="mdi mdi-room-service"></i>
                                    <span>
                                        {{ $order->service?->getCurrentNameAttribute() 
                                            ?? $order->userPackage?->package?->getCurrentNameAttribute() 
                                            ?? 'غير محدد' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Booking Date --}}
                            <td>
                                <div class="date-cell">
                                    <div><i class="mdi mdi-calendar"></i> {{ $order->booking_date }}</div>
                                    <div><i class="mdi mdi-clock-outline"></i> {{ $order->booking_time }}</div>
                                </div>
                            </td>

                            {{-- Price --}}
                            <td>
                                <span class="price-cell">{{ $order->total_price }} ريال</span>
                            </td>

                            {{-- Order Status --}}
                            <td>
                                @if ($order->order_status_id == 4)
                                    <span class="status-badge completed">
                                        <i class="mdi mdi-check-circle"></i>
                                        {{ $order->orderStatus->name }}
                                    </span>
                                @elseif($order->order_status_id == 2)
                                    <span class="status-badge processing">
                                        <i class="mdi mdi-progress-clock"></i>
                                        {{ $order->orderStatus->name }}
                                    </span>
                                @elseif($order->order_status_id == 3)
                                    <span class="status-badge assigned">
                                        <i class="mdi mdi-account-check"></i>
                                        {{ $order->orderStatus->name }}
                                    </span>
                                @elseif($order->order_status_id == 11)
                                    <span class="status-badge cancelled">
                                        <i class="mdi mdi-close-circle"></i>
                                        {{ $order->orderStatus->name }}
                                    </span>
                                @elseif($order->order_status_id == 12)
                                    <span class="status-badge pending">
                                        <i class="mdi mdi-clock-outline"></i>
                                        {{ $order->orderStatus->name }}
                                    </span>
                                @else
                                    <span class="status-badge pending">
                                        <i class="mdi mdi-help-circle"></i>
                                        {{ $order->orderStatus->name ?? 'غير محدد' }}
                                    </span>
                                @endif
                                
                                @if ($order->updated_by_admin)
                                    <div style="font-size: 11px; color: #9ca3af; margin-top: 2px;">
                                        <i class="mdi mdi-account-cog"></i> مسئول
                                    </div>
                                @endif
                            </td>

                            {{-- Payment Status --}}
                            <td>
                                @if($order->payment_status == 'paid')
                                    <span class="payment-badge paid">
                                        <i class="mdi mdi-check-circle"></i>
                                        مدفوع
                                    </span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="payment-badge pending">
                                        <i class="mdi mdi-clock-outline"></i>
                                        قيد الانتظار
                                    </span>
                                @elseif($order->payment_status == 'failed')
                                    <span class="payment-badge failed">
                                        <i class="mdi mdi-close-circle"></i>
                                        فشل
                                    </span>
                                @else
                                    <span class="payment-badge pending">
                                        <i class="mdi mdi-help-circle"></i>
                                        غير محدد
                                    </span>
                                @endif
                            </td>

                            {{-- Captain --}}
                            <td>
                                @if($order->captain)
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                            {{ mb_substr($order->captain->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('captains.show', $order->captain->id) }}" style="font-size: 13px; font-weight: 600; color: #1f2937; text-decoration: none;">
                                                {{ $order->captain->name }}
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <span style="color: #9ca3af; font-size: 13px;">
                                        <i class="mdi mdi-account-off"></i> غير مُعيّن
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('orders.show', $order->id) }}" 
                                       class="btn-action view" 
                                       title="مشاهدة">
                                        <i class="mdi mdi-eye"></i>
                                    </a>

                                    @can('order.delete')
                                        <form method="post" 
                                              id="formDelete_{{ $order->id }}"
                                              action="{{ route('orders.destroy', $order->id) }}"
                                              style="display: inline;">
                                            @csrf
                                            @method('delete')
                                            <button type="button" 
                                                    class="btn-action delete"
                                                    title="حذف"
                                                    onclick="confirmDelete({{ $order->id }})">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="mdi mdi-cart-off"></i>
                                    <h5>لا توجد طلبات</h5>
                                    <p>لم يتم العثور على أي طلبات بناءً على معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="pagination">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
@push('scripts')

    <script>
        $(document).ready(function () {
            $('#datatable').DataTable({
                paging: false, // Disable DataTables pagination
                searching: true, // Enable searching
                ordering: true, // Enable column ordering
                info: false, // Disable DataTables' "Showing X to Y of Z entries"
                order: [[0, 'desc']], // Order by the first column (created_at) in descending order
                columnDefs: [
                    {
                        targets: 0, // The column index for "created_at"
                        type: 'date' // Ensure it recognizes the date format for proper sorting
                    }
                ]
            });
        });
    </script>

    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Bootstrap datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
@endpush