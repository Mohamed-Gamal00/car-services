@extends('dashboard.index')
@section('title', 'العملاء')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">العملاء</li>
@endsection

@section('section')
    <style>
        .client-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .client-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .stats-card:hover {
            transform: translateX(-5px);
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
        .client-avatar {
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
        .client-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 2px;
        }
        .client-phone {
            color: #6c757d;
            font-size: 0.875rem;
            direction: ltr;
            text-align: right;
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
        .btn-view {
            background: #28a745;
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
        .date-badge {
            background: #f8f9fa;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.875rem;
            color: #495057;
        }
    </style>

    <!-- Statistics Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2" style="opacity: 0.9;">إجمالي العملاء</h6>
                            <h2 class="mb-0 fw-bold">{{ $clients->total() }}</h2>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.3;">
                            <i class="mdi mdi-account-group"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card client-card">
                <div class="card-body">
                    <x-alert type='success'/>
                    <x-alert type='dark'/>
                    <x-alert type='danger'/>

                    <!-- Search Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ URL::current() }}" method="get">
                                <div class="search-box">
                                    <i class="mdi mdi-magnify"></i>
                                    <input type="text" 
                                           name="phone" 
                                           class="form-control" 
                                           placeholder="البحث عن طريق رقم الجوال أو اسم العميل..."
                                           value="{{ request('phone') }}">
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th><i class="mdi mdi-account me-1"></i>العميل</th>
                                    <th><i class="mdi mdi-phone me-1"></i>رقم الجوال</th>
                                    <th><i class="mdi mdi-calendar me-1"></i>تاريخ التسجيل</th>
                                    <th class="text-center"><i class="mdi mdi-cog me-1"></i>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clients as $client)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="client-avatar">
                                                    {{ mb_substr($client->name, 0, 1) }}
                                                </div>
                                                <div class="ms-3">
                                                    <div class="client-name">{{ $client->name }}</div>
                                                    <small class="text-muted">#{{ $client->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="mdi mdi-cellphone text-primary me-2"></i>
                                                <span class="client-phone">{{ $client->phone }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="date-badge">
                                                <i class="mdi mdi-clock-outline me-1"></i>
                                                {{ $client->created_at->format('Y-m-d') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                @can('client.edit')
                                                    <a href="{{ route('clients.edit', $client->id) }}" 
                                                       class="action-btn btn-edit" 
                                                       title="تعديل">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                @endcan
                                                <a href="{{ route('clients.show', $client->id) }}" 
                                                   class="action-btn btn-view" 
                                                   title="عرض الطلبات">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state">
                                                <i class="mdi mdi-account-off-outline"></i>
                                                <h5 class="text-muted">لا يوجد عملاء</h5>
                                                <p class="text-muted">لم يتم العثور على أي عملاء في الوقت الحالي</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($clients->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $clients->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit search form on input with debounce
        let searchTimeout;
        document.querySelector('input[name="phone"]')?.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    </script>
@endsection
