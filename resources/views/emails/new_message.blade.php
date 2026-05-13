<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسالة جديدة</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7fa; padding: 20px; direction: rtl; margin: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center; color: #ffffff;">
                <div style="width: 70px; height: 70px; background-color: rgba(255, 255, 255, 0.2); border-radius: 50%; display: inline-block; line-height: 70px; margin-bottom: 20px;">
                    📧
                </div>
                <h1 style="font-size: 28px; font-weight: 600; margin: 0 0 8px 0;">رسالة جديدة من العميل</h1>
                <p style="font-size: 16px; margin: 0; opacity: 0.95;">تم استلام رسالة جديدة عبر نموذج التواصل</p>
            </td>
        </tr>
        
        <!-- Body -->
        <tr>
            <td style="padding: 40px 30px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fc; border-right: 4px solid #667eea; border-radius: 8px; padding: 25px;">
                    <tr>
                        <td style="padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                            <strong style="color: #374151; font-size: 15px;">اسم المرسل:</strong><br>
                            <span style="color: #6b7280; font-size: 15px;">{{ $contactMessage->name ?? 'غير متوفر' }}</span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="padding: 20px 0; border-bottom: 1px solid #e5e7eb;">
                            <strong style="color: #374151; font-size: 15px;">البريد الإلكتروني:</strong><br>
                            <a href="mailto:{{ $contactMessage->email ?? '' }}" style="color: #667eea; text-decoration: none; font-size: 15px;">
                                {{ $contactMessage->email ?? 'غير متوفر' }}
                            </a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="padding: 20px 0; border-bottom: 1px solid #e5e7eb;">
                            <strong style="color: #374151; font-size: 15px;">رقم الهاتف:</strong><br>
                            <a href="tel:{{ $contactMessage->phone_number ?? '' }}" style="color: #667eea; text-decoration: none; font-size: 15px;">
                                {{ $contactMessage->phone_number ?? 'غير متوفر' }}
                            </a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="padding-top: 20px;">
                            <strong style="color: #374151; font-size: 15px;">الرسالة:</strong><br>
                            <div style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-top: 10px; line-height: 1.8; color: #374151; font-size: 15px;">
                                {{ $contactMessage->message ?? 'لا توجد رسالة' }}
                            </div>
                        </td>
                    </tr>
                </table>
                
                <div style="text-align: center; margin-top: 30px;">
                    @if(isset($contactMessage->created_at))
                    <span style="display: inline-block; background-color: #e0e7ff; color: #4338ca; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 500;">
                        📅 {{ $contactMessage->created_at->format('Y-m-d H:i') }}
                    </span>
                    @endif
                </div>
            </td>
        </tr>
        
        <!-- Footer -->
        <tr>
            <td style="background-color: #f8f9fc; padding: 25px 30px; text-align: center; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb;">
                <p style="margin: 0 0 8px 0;"><strong>نظام إدارة خدمات السيارات</strong></p>
                <p style="margin: 0 0 8px 0;">هذا البريد الإلكتروني تم إرساله تلقائياً، يرجى عدم الرد عليه</p>
                <p style="margin: 15px 0 0 0; font-size: 13px;">
                    © {{ date('Y') }} جميع الحقوق محفوظة
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
