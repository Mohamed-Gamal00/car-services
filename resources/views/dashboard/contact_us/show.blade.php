@extends('dashboard.index')

@section('title', 'مشاهدة الرسالة')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('contact_us.index')}}">الرسائل</a></li>
    <li class="breadcrumb-item active">مشاهدة الرسالة</li>
@endsection

@section('section')
    <style>
        .message-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .message-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 12px;
            overflow: hidden;
        }
        .message-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
        }
        .sender-profile {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .sender-avatar-large {
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
        .sender-info-header {
            flex: 1;
        }
        .sender-name-large {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .sender-meta {
            opacity: 0.9;
            font-size: 0.95rem;
        }
        .message-body {
            padding: 30px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-row {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .info-row:hover {
            background: #e9ecef;
            transform: translateX(-5px);
        }
        .info-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            margin-left: 15px;
        }
        .info-content {
            flex: 1;
        }
        .info-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .info-value {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1rem;
        }
        .message-content-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            border-left: 4px solid #667eea;
        }
        .message-content-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
        }
        .message-text {
            color: #495057;
            line-height: 1.8;
            font-size: 1rem;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        .btn-back {
            background: #6c757d;
            border: none;
            padding: 12px 40px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
        }
        .btn-reply {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        .btn-reply:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .timestamp-badge {
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    <div class="message-container">
        <div class="card message-card">
            <!-- Header -->
            <div class="message-header">
                <div class="sender-profile">
                    <div class="sender-avatar-large">
                        {{ mb_substr($message->name, 0, 1) }}
                    </div>
                    <div class="sender-info-header">
                        <div class="sender-name-large">{{ $message->name }}</div>
                        <div class="sender-meta">
                            <i class="mdi mdi-email-outline me-1"></i>
                            {{ $message->email }}
                        </div>
                        <div class="timestamp-badge mt-2">
                            <i class="mdi mdi-clock-outline"></i>
                            {{ $message->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="message-body">
                <!-- Contact Information -->
                <div class="info-section">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-icon">
                                    <i class="mdi mdi-account"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">الاسم</div>
                                    <div class="info-value">{{ $message->name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-icon">
                                    <i class="mdi mdi-phone"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">رقم الجوال</div>
                                    <div class="info-value">{{ $message->phone_number }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon">
                            <i class="mdi mdi-email"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">البريد الإلكتروني</div>
                            <div class="info-value">{{ $message->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Message Content -->
                <div class="message-content-section">
                    <div class="message-content-header">
                        <i class="mdi mdi-message-text"></i>
                        محتوى الرسالة
                    </div>
                    <div class="message-text">{{ $message->message }}</div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('contact_us.index') }}" class="btn btn-back">
                        <i class="mdi mdi-arrow-right me-2"></i>
                        العودة للرسائل
                    </a>
                    <a href="mailto:{{ $message->email }}" class="btn btn-reply">
                        <i class="mdi mdi-reply me-2"></i>
                        الرد على الرسالة
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
