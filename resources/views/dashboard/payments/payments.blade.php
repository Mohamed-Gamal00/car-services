@extends('dashboard.index')
@section('title', 'المدفوعات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">المدفوعات</li>
@endsection

@section('section')
    <style>
        .payment-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .payment-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }
        .filter-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .search-box {
            position: relative;
        }
        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
        }
        .search-box input {
            padding-right: 45px;
            border-radius: 25px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }
        .search-box input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        .stats-card {
            border-left: 4px solid;
            transition: all 0.3s;
        }
        .stats-card:hover {
            transform: translateX(-5px);
        }
        .stats-card.success {
            border-left-color: #28a745;
        }
        .stats-card.warning {
            border-left-color: #ffc107;
        }
        .stats-card.danger {
            border-left-color: #dc3545;
        }
        .payment-amount {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
        }
        .payment-id {
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
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
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        .filter-btn {
            border-radius: 20px;
            padding: 8px 20px;
            font-size: 0.875rem;
            transition: all 0.3s;
        }
        .filter-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .client-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .client-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
    </style>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stats-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">المدفوعات الناجحة</h6>
                            <h3 class="mb-0 text-success">{{ $payments->where('status', 'paid')->count() }}</h3>
                        </div>
                        <div class="text-success" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="mdi mdi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">قيد الانتظار</h6>
                            <h3 class="mb-0 text-warning">{{ $payments->where('status', 'pending')->count() }}</h3>
                        </div>
                        <div class="text-warning" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="mdi mdi-clock-outline"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">المدفوعات الفاشلة</h6>
                            <h3 class="mb-0 text-danger">{{ $payments->where('status', 'failed')->count() }}</h3>
                        </div>
                        <div class="text-danger" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="mdi mdi-close-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card payment-card">
                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <form action="{{ URL::current() }}" method="get">
                                <div class="search-box">
                                    <i class="mdi mdi-magnify"></i>
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="البحث عن طريق رقم الطلب، العميل، أو رقم العملية..."
                                           value="{{ request('search') }}">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 d-flex gap-2 align-items-center">
                            <a href="{{ URL::current() }}" class="btn btn-outline-secondary filter-btn {{ !request('status') ? 'active' : '' }}">
                                الكل
                            </a>
                            <a href="{{ URL::current() }}?status=paid" class="btn btn-outline-success filter-btn {{ request('status') == 'paid' ? 'active' : '' }}">
                                مدفوع
                            </a>
                            <a href="{{ URL::current() }}?status=pending" class="btn btn-outline-warning filter-btn {{ request('status') == 'pending' ? 'active' : '' }}">
                                معلق
                            </a>
                            <a href="{{ URL::current() }}?status=failed" class="btn btn-outline-danger filter-btn {{ request('status') == 'failed' ? 'active' : '' }}">
                                فاشل
                            </a>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><i class="mdi mdi-file-document-outline me-1"></i>رقم الطلب</th>
                                    <th><i class="mdi mdi-package-variant me-1"></i>الباقة</th>
                                    <th><i class="mdi mdi-account me-1"></i>العميل</th>
                                    <th><i class="mdi mdi-information me-1"></i>الحالة</th>
                                    <th><i class="mdi mdi-cash me-1"></i>المبلغ</th>
                                    <th><i class="mdi mdi-credit-card me-1"></i>البطاقة</th>
                                    <th><i class="mdi mdi-identifier me-1"></i>رقم العملية</th>
                                    <th><i class="mdi mdi-calendar me-1"></i>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-muted">{{ $loop->iteration }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">#{{ $payment->order_number }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $payment->package_reference ?? 'غير محدد' }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('clients.edit', $payment->user_id) }}" class="client-link">
                                                <i class="mdi mdi-account-circle me-1"></i>
                                                {{ $payment->user_name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($payment->status == 'paid')
                                                <span class="status-badge status-paid">
                                                    <i class="mdi mdi-check-circle"></i>
                                                    مدفوع
                                                </span>
                                            @elseif($payment->status == 'pending')
                                                <span class="status-badge status-pending">
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    معلق
                                                </span>
                                            @elseif($payment->status == 'failed')
                                                <span class="status-badge status-failed">
                                                    <i class="mdi mdi-close-circle"></i>
                                                    فاشل
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="payment-amount">
                                                {{ number_format($payment->amount / 100, 2) }}
                                                <small class="text-muted">{{ $payment->cur }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="mdi mdi-credit-card-outline me-2 text-primary"></i>
                                                <span>{{ $payment->source ?? 'غير محدد' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="payment-id">{{ $payment->payment_id }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold">{{ $payment->created_at->format('Y-m-d') }}</div>
                                                <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">
                                            <div class="empty-state">
                                                <i class="mdi mdi-credit-card-off-outline"></i>
                                                <h5 class="text-muted">لا توجد مدفوعات</h5>
                                                <p class="text-muted">لم يتم العثور على أي مدفوعات في الوقت الحالي</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($payments->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $payments->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit search form on input with debounce
        let searchTimeout;
        document.querySelector('input[name="search"]')?.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    </script>
@endsection
