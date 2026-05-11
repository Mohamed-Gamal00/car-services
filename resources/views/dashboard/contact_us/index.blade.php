@extends('dashboard.index')
@section('title', 'الرسائل')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active" aria-current="page">الرسائل</li>
@endsection

@section('section')
    <style>
        .messages-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .messages-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 12px;
            overflow: hidden;
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
            margin-bottom: 25px;
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
        .sender-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sender-avatar {
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
        .sender-details {
            flex: 1;
        }
        .sender-name {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 2px;
        }
        .sender-email {
            color: #6c757d;
            font-size: 0.85rem;
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
        .date-display {
            color: #6c757d;
            font-size: 0.875rem;
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
            color: white;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .btn-view {
            background: #667eea;
        }
        .btn-view.unread {
            background: #dc3545;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .unread-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 12px;
            height: 12px;
            background: #dc3545;
            border-radius: 50%;
            border: 2px solid white;
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
    </style>

    <div class="messages-container">
        <!-- Statistics Card -->
        <div class="stats-card">
            <div class="stats-content">
                <div class="stats-info">
                    <h6>إجمالي الرسائل</h6>
                    <h2>{{ $messages->total() }}</h2>
                    <small style="opacity: 0.8;">
                        <i class="mdi mdi-email-alert"></i>
                        {{ $notifications->whereNull('read_at')->count() }} رسالة جديدة
                    </small>
                </div>
                <div class="stats-icon">
                    <i class="mdi mdi-email-multiple"></i>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card messages-card">
            <div class="card-body p-4">
                <x-alert type='success'/>
                <x-alert type='warning'/>
                <x-alert type='dark'/>

                <!-- Header Section -->
                <div class="header-section">
                    <h5 class="mb-1" style="color: #2c3e50; font-weight: 700;">
                        <i class="mdi mdi-email-outline me-2"></i>
                        رسائل التواصل
                    </h5>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">
                        عرض وإدارة جميع رسائل التواصل الواردة من العملاء
                    </p>
                </div>

                <!-- Table Section -->
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th><i class="mdi mdi-account me-1"></i>معلومات المرسل</th>
                                <th><i class="mdi mdi-phone me-1"></i>رقم الجوال</th>
                                <th><i class="mdi mdi-calendar me-1"></i>تاريخ الإرسال</th>
                                <th class="text-center"><i class="mdi mdi-eye me-1"></i>مشاهدة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($messages as $message)
                                <tr>
                                    <td>
                                        <div class="sender-info">
                                            <div class="sender-avatar">
                                                {{ mb_substr($message->name, 0, 1) }}
                                            </div>
                                            <div class="sender-details">
                                                <div class="sender-name">{{ $message->name }}</div>
                                                <div class="sender-email">
                                                    <i class="mdi mdi-email-outline"></i>
                                                    {{ $message->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="phone-badge">
                                            <i class="mdi mdi-cellphone"></i>
                                            {{ $message->phone_number }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-display">
                                            <i class="mdi mdi-clock-outline me-1"></i>
                                            {{ $message->created_at->format('Y-m-d H:i') }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @can('discounts.edit')
                                            @php
                                                $notification = $notifications->where('data.message_id', $message->id)->first();
                                                $isUnread = $notification && is_null($notification->read_at);
                                            @endphp
                                            <div style="position: relative; display: inline-block;">
                                                <a href="{{ route('contact_us.watch', $message->id) }}?notification_id={{ $notification?->id }}"
                                                   class="action-btn btn-view {{ $isUnread ? 'unread' : '' }}" 
                                                   title="{{ $isUnread ? 'رسالة جديدة' : 'مشاهدة' }}">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                @if($isUnread)
                                                    <span class="unread-badge"></span>
                                                @endif
                                            </div>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="mdi mdi-email-off-outline"></i>
                                            <h5 class="text-muted">لا توجد رسائل</h5>
                                            <p class="text-muted">لم يتم استلام أي رسائل تواصل حتى الآن</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
