<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ContactUs;
use App\Notifications\ContactFormSubmitted;
use App\Notifications\NewMessageEmail;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactUsController extends Controller
{
    public function index()
    {
        return view('front.contact_us.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'phone_number' => 'required|numeric',
            'text' => 'required|string'
        ]);

        $form = ContactUs::create([
            'name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'email' => $request->contact_email,
            'message' => $request->text
        ]);

        $admins = Admin::all();
        Notification::send($admins, new ContactFormSubmitted($form));

        foreach ($admins as $admin) {
            try {
                if (filter_var($admin->email, FILTER_VALIDATE_EMAIL)) {
                    Notification::send($admin, new NewMessageEmail($form));
                } else {
                    \Log::warning('Invalid email address: ' . $admin->email);
                }
            } catch (\Exception $e) {
                \Log::error('Error sending email to admin ' . $admin->email . ': ' . $e->getMessage());
            }
        }

        return \redirect()->back()->with('success', 'تم الارسال بنجاح');
    }
}
