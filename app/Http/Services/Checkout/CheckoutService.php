<?php

namespace App\Http\Services\Checkout;

use App\Helper\ApiResponse;
use App\Helper\Helper;
use App\Http\Services\DiscountHandler;
use App\Models\Admin;
use App\Models\Captain;
use App\Models\Choice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Service;
use App\Models\Package;
use App\Models\Setting;
use App\Models\UserPackage;
use App\Notifications\CaptainAssignedNotification;
use App\Notifications\OrderCreatedEmailAdmin;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;


class CheckoutService
{
    use Helper;

    public function checkUserPackage($request, $user)
    {
        $userPackage = UserPackage::where('package_id', $request->user_package_id)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$userPackage) {
            throw new \Exception(__('general.The_package_is_invalid_or_has_been_fully_consumed'), 422);
        }

        // Check if expired انتهت الاصلاحية ولا لا
        if ($userPackage->expiry_date < now()) {
            $userPackage->update(['status' => 'expired']);
            throw new \Exception(__('general.The_package_has_expired'), 422);
        }

        // Check if consumed الباقة استهلكت ولا لا
        if ($userPackage->remaining_washes <= 0) {
            $userPackage->update(['status' => 'used_up']);
            throw new \Exception(__('general.The_package_is_invalid_or_has_been_fully_consumed'), 400);
        }

        return $userPackage;
    }

    public function checkTotalPriceOrder($package, $request)
    {
        $additionalServicePrice = 0;

        if ($request->has('choices')) {
            // لو عندي خدمات اضافية الكلاينت اختارها هضيفها علي قيمة الخدمة الاساسية
            $choices = Choice::whereIn('id', $request->choices)->get();

            //  هجيب قيمة كل هخدمة اضافية اتاضفت واجمعها
            foreach ($choices as $choice) {
                $additionalServicePrice += $choice->service_price;
            }
        }

        $orderTotalPrice = $package->price + $additionalServicePrice; //   سعر الخدمات الاضافية مع سعر الباقة

        return $orderTotalPrice;
    }

    public function checkTimeReservation($request)
    {
        $setting = Setting::first();
        $workStartTime = $setting->working_strat_time;
        $workEndTime = $setting->working_end_time;

        // Convert the work start and end times to timestamps for comparison
        $workStartTimeStamp = strtotime($workStartTime);
        $workEndTimeStamp = strtotime($workEndTime);

        $bookingTime = $request->booking_time;
        // new
        $bookingDate = $request->booking_date;
        $bookingDateTime = strtotime($bookingDate . ' ' . $bookingTime); // Full timestamp

        $bookingTimeStamp = strtotime($bookingTime);

        if ($bookingTimeStamp < $workStartTimeStamp || $bookingTimeStamp > $workEndTimeStamp) {
            throw new \Exception(translateWithHTMLTags('وقت الحجز غير صالح. يجب أن يكون من ') . $workStartTime . ' : ' . $workEndTime . '.', 422);
        }

        $currentDate = date('Y-m-d'); // Today's date
        $currentTimeStamp = time(); // Current timestamp
        $minimumBookingTimeStamp = $currentTimeStamp + (30 * 60); // 30 minutes ahead

        // Apply 30-minute rule **only if booking is for today**
        if ($bookingDate == $currentDate && $bookingDateTime < $minimumBookingTimeStamp) {
            throw new \Exception(translateWithHTMLTags('يجب أن يكون وقت الحجز على الأقل 30 دقيقة قبل وقت الخدمة'), 422);
        }
    }

    public function createOrder($request, $package, $orderTotalPrice, $captain, $userPackage)
    {
        // Get default order status
        $defaultStatus = OrderStatus::where('default_status', true)->first();
        if (!$defaultStatus) {
            // Fallback to first status if no default is set
            $defaultStatus = OrderStatus::first();
            if (!$defaultStatus) {
                throw new \Exception('لا توجد حالات طلب متاحة في النظام', 500);
            }
        }

        $order = Order::create([
            'user_id' => $request->user()->id,
            'service_id' => $package->id,
            'order_status_id' => $captain ? 3 : $defaultStatus->id,
            'car_id' => $request->car_id,
            'car_model' => $request->car_model,
            'car_number' => $request->car_number,
            'image' => $request->image,
            'note' => $request->note,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location' => $request->location,
            'totalBeforeDiscount' => $orderTotalPrice,
            'total_price' => $orderTotalPrice,
            'payment_method' => 'package',
            'payment_status' => 'paid',
            'captain_id' => $captain->id ?? null,
            'user_package_id' => $userPackage->id,
        ]);

        if ($captain) {
            $captain->notify(new CaptainAssignedNotification($order));
            $captain->status = 'busy';
            $captain->save();
            $captain = Captain::findOrFail($order->captain_id);
            app()->setLocale($captain->lang ?? 'ar');
            $tokens = $captain->devicetokens->pluck('token')->toArray();
            Log::info('Captain Device Tokens', ['captain_id' => $captain->id]);

            if ($tokens) {
                $data = ['order_id' => $order->id];
                $this->notifyByFirebase(__('general.new_notification'), __('general.There_is_a_new_request_for_you'), $tokens, $data);
                Log::info('Notification Sent to Firebase', ['tokens' => $tokens, 'data' => $data]);
            } else {
                Log::error('No device tokens for captain', ['captain_id' => $captain->id]);
            }
        }

        // خدمات إضافية - validate choice IDs before attaching
        if ($request->has('choices') && is_array($request->choices)) {
            // Filter out invalid/null choice IDs and validate they exist
            $validChoiceIds = Choice::whereIn('id', array_filter($request->choices))
                ->pluck('id')
                ->toArray();
            
            if (!empty($validChoiceIds)) {
                $order->choices()->attach($validChoiceIds);
            }
        }

        // Handle order images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('uploads/order_images', 'public');
                $order->images()->create([
                    'image' => $path
                ]);
            }
        }

        return $order;
    }

    public function sendNotificationToAdmin($order)
    {
        try {
            // Load all necessary relationships before sending notifications
            $order->load(['user', 'car', 'service', 'userPackage.package', 'choices']);
            
            $admins = Admin::where('is_super_admin',1)->get();

            // Send database notification (doesn't require email rendering)
            try {
                Notification::send($admins, new OrderCreatedNotification($order));
                Log::info('Database notification sent to admins', ['order_id' => $order->id]);
            } catch (\Exception $e) {
                Log::warning('Failed to send database notification to admins', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Send email notifications
            $validAdmins = $admins->filter(function ($admin) {
                return filter_var($admin->email, FILTER_VALIDATE_EMAIL);
            });

            foreach ($validAdmins as $admin) {
                try {
                    Notification::route('mail', $admin->email)
                        ->notify(new OrderCreatedEmailAdmin($order));
                    Log::info('Email notification sent to admin', [
                        'admin_email' => $admin->email,
                        'order_id' => $order->id
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to send email notification to admin', [
                        'admin_email' => $admin->email,
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continue to next admin
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send notifications to admins', [
                'order_id' => $order->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Don't throw - allow checkout to continue even if notifications fail
        }
    }

    public function createOrderFromService($request, $service_id, $user)
    {
        if (!$service_id) {
            throw new \Exception('يجب تحديد service_id أو user_package_id', 422);
        }

        $service = Service::findOrFail($service_id);
        $orderServicePrice = $service->price;
        $additionalServicePrice = 0;

        if ($request->has('choices')) {
            $choices = Choice::whereIn('id', $request->choices)->get();
            foreach ($choices as $choice) {
                $additionalServicePrice += $choice->service_price;
            }
        }

        $orderTotalPrice = $orderServicePrice + $additionalServicePrice;

        $this->checkTimeReservation($request);

        // Get default order status
        $defaultStatus = OrderStatus::where('default_status', true)->first();
        if (!$defaultStatus) {
            // Fallback to first status if no default is set
            $defaultStatus = OrderStatus::first();
            if (!$defaultStatus) {
                throw new \Exception('لا توجد حالات طلب متاحة في النظام', 500);
            }
        }

        $order = Order::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'order_status_id' => $defaultStatus->id,
            'car_id' => $request->car_id,
            'car_model' => $request->car_model,
            'car_number' => $request->car_number,
            'image' => $request->image,
            'note' => $request->note,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->location,
            'totalBeforeDiscount' => $orderTotalPrice,
            'total_price' => $orderTotalPrice,
            'payment_method' => $request->payment_method,
            'captain_id' => null,
        ]);

        // خصم
        if ($request->has('discount_code')) {
            $discountResponse = app()->make(DiscountHandler::class)
                ->applyDiscount($request->discount_code, $service_id, $orderTotalPrice, $user->id);

            if ($discountResponse['status'] === 'error') {
                throw new \Exception($discountResponse['message'], 400);
            }

            $order->update([
                'total_price' => $discountResponse['final_price'],
                'discount_applied' => $request->discount_code,
            ]);
        }

        // خدمات إضافية - validate choice IDs before attaching
        if ($request->has('choices') && is_array($request->choices)) {
            // Filter out invalid/null choice IDs and validate they exist
            $validChoiceIds = Choice::whereIn('id', array_filter($request->choices))
                ->pluck('id')
                ->toArray();
            
            if (!empty($validChoiceIds)) {
                $order->choices()->attach($validChoiceIds);
            }
        }

        // صور
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('uploads/order_images', 'public');
                $order->images()->create(['image_path' => $path]);
            }
        }

        return $order;
    }
}