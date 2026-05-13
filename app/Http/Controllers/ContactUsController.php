<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Requests\Api\ContactUsRequest;
use App\Mail\NewContactMessage;
use App\Models\Admin;
use App\Models\ContactUs;
use App\Notifications\ContactFormSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ContactUsController extends Controller
{
    public function sendMessage(ContactUsRequest $request)
    {
        $request->validated();
        $form = ContactUs::create([
            'name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'message' => $request->message
        ]);

        $admins = Admin::all();

        if ($form) {
            // Send database notification to all admins
            Notification::send($admins, new ContactFormSubmitted($form));

            // Send email notification only to admins with valid emails
            foreach ($admins as $admin) {
                if (!empty($admin->email) && filter_var($admin->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        Mail::to($admin->email)->send(new NewContactMessage($form));
                    } catch (\Exception $e) {
                        \Log::error('Error sending email to admin ' . $admin->email . ': ' . $e->getMessage());
                    }
                } else {
                    \Log::warning('Skipping email notification for admin with invalid email: ' . ($admin->email ?? 'null'));
                }
            }
            
            return ApiResponse::sendResponse(200, 'Message Sent Successfully', []);
        }
        
        return ApiResponse::sendResponse(400, 'Cannot Send Message', []);
    }
}