<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Http\Services\Checkout\CheckoutService;
use App\Http\Services\DiscountHandler;
use App\Http\Services\SendNotification;
use App\Jobs\AssignCaptainToOrder;
use App\Jobs\MakeCaptainAvailableJob;
use App\Models\Admin;
use App\Models\Captain;
use App\Models\Cart;
use App\Models\Choice;
use App\Models\DeviceToken;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Package;
use App\Models\Service;
use App\Models\Setting;
use App\Models\UserPackage;
use App\Notifications\CaptainAssignedNotification;
use App\Notifications\OrderCreatedEmailAdmin;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Intl\Countries;
use Throwable;

class CheckoutController extends Controller
{
    use Helper;

    protected $checkOutservice;

    protected $discountHandler;

    public function __construct(DiscountHandler $discountHandler, CheckoutService $checkOutservices)
    {
        $this->discountHandler = $discountHandler;

        $this->checkOutservice = $checkOutservices;
    }

    public function usercheckout(CheckoutRequest $request, $service_id = null)
    {
        $user = $request->user();
        DB::beginTransaction();

        try {
            $isPackage = $request->has('user_package_id');
            $orderTotalPrice = 0;
            
            Log::info('Checkout started', [
                'service_id' => $service_id,
                'is_package' => $isPackage,
                'user_package_id' => $request->user_package_id ?? null
            ]);

            if ($isPackage) {
                //  طلب من باقة
                $userPackage = $this->checkOutservice->checkUserPackage($request, $user);
                $package = Package::findOrFail($userPackage->package_id);
                $orderTotalPrice = $this->checkOutservice->checkTotalPriceOrder($package, $request);

                $this->checkOutservice->checkTimeReservation($request);
                $availableCaptains = Captain::where('status', 'available')->where('is_active', 1)->get();
                $captain = $availableCaptains->shift();

                $order = $this->checkOutservice->createOrder($request, $package, $orderTotalPrice, $captain, $userPackage);

                
                // Load relationships needed for invoice generation
                $order->load(['user', 'car', 'choices', 'userPackage.package']);
                
                // Generate invoice - wrap in try-catch to prevent checkout failure
                try {
                    $invoicePath = $this->checkOutservice->generateInvoicePDF($order);
                    $order->update(['invoice_url' => $invoicePath]);
                } catch (\Exception $e) {
                    Log::error('Invoice generation failed during checkout', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                    // Continue without invoice - it can be generated later
                }
                $userPackage->decrement('remaining_washes');

                $userPackage->refresh(); // علشان نجيب القيمة الجديدة من قاعدة البيانات

                if ($userPackage->remaining_washes <= 0) {
                    $userPackage->update(['status' => 'used_up']);
                }
                $tokens = $captain->devicetokens->pluck('token')->toArray();
                if ($tokens) {
                    $data = ['order_id' => $order->id];
                    $this->notifyByFirebase(__('general.new_notification'), __('general.There_is_a_new_request_for_you'), $tokens, $data);
                    Log::info('Notification Sent to Firebase', ['tokens' => $tokens, 'data' => $data]);

                } else {
                    Log::error('No device tokens for captain', ['captain_id' => $captain->id]);
                }

                if ($captain) {
                    MakeCaptainAvailableJob::dispatch($captain->id)
                        ->delay(now()->addMinutes($this->convertTimeToMinutes($package->duration)));
                }

                //gt() greater than
                if (now()->gt($userPackage->expiry_date)) {
                    $userPackage->update(['status' => 'expired']);
                }
            } else {
                $order = $this->checkOutservice->createOrderFromService($request, $service_id, $user);
            }

            if ($request->save_car_details == true) {
                $user->cars()->syncWithoutDetaching([
                    $request->car_id => [
                        'car_model' => $request->car_model,
                        'car_number' => $request->car_number,
                    ]
                ]);
            }

            if ($request->save_address_details) {
                $existingAddress = $user->addresses()->where([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'address' => $request->location,
                ])->first();

                if (!$existingAddress) {
                    $user->addresses()->create([
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'address' => $request->location,
                        'user_id' => $user->id,
                    ]);
                }
            }

            if ($request->has('choices') && is_array($request->choices)) {
                // Filter out invalid/null choice IDs and validate they exist
                $validChoiceIds = Choice::whereIn('id', array_filter($request->choices))
                    ->pluck('id')
                    ->toArray();
                
                if (!empty($validChoiceIds)) {
                    $order->choices()->attach($validChoiceIds);
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/order_images', 'public');
                    $order->images()->create(['image' => $path]);
                }
            }

            // $this->checkOutservice->sendNotificationToAdmin($order);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => __('general.Order_creation_success'),
                'payment_url' => !$isPackage ? route('user.payment', ['order_number' => $order->number, 'method' => $request->payment_method]) : null,
                'final_price' => $order->total_price,
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'faild', 'message' => __('general.Order_creation_failed'), 'error' => $e->getMessage()], 500);
        }
    }


//    public function usercheckout(CheckoutRequest $request, $service_id)
//    {
//
//        $user = $request->user();
//        $service = Product::findOrFail($service_id);
//
//        $orderServicePrice = $service->price;
//        $additionalServicePrice = 0;
//
//        if ($request->has('choices')) {
//            // لو عندي خدمات اضافية الكلاينت اختارها هضيفها علي قيمة الخدمة الاساسية
//            $choices = Choice::whereIn('id', $request->choices)->get();
//
//            //  هجيب قيمة كل هخدمة اضافية اتاضفت واجمعها
//            foreach ($choices as $choice) {
//                $additionalServicePrice += $choice->service_price;
//            }
//        }
//
//        // Validate request
//        $request->validated();
//        $orderTotalPrice = $orderServicePrice + $additionalServicePrice;
//
//        /*check time reserve*/
//        $setting = Setting::first();
//        $workStartTime = $setting->working_strat_time;
//        $workEndTime = $setting->working_end_time;
//
//        // Convert the work start and end times to timestamps for comparison
//        // strtotime() function in PHP is used to convert a date/time string into a Unix timestamp (the number of seconds since January 1, 1970, 00:00:00 UTC)
//        $workStartTimeStamp = strtotime($workStartTime);
//        $workEndTimeStamp = strtotime($workEndTime);
//
//        $bookingTime = $request->booking_time;
//        // new
//        $bookingDate = $request->booking_date;
//        $bookingDateTime = strtotime($bookingDate . ' ' . $bookingTime); // Full timestamp
//
//        $bookingTimeStamp = strtotime($bookingTime);
//
//        if ($bookingTimeStamp < $workStartTimeStamp || $bookingTimeStamp > $workEndTimeStamp) {
//            return response()->json([
//                'status' => 'error',
//                'message' => translateWithHTMLTags('وقت الحجز غير صالح. يجب أن يكون من ') . $workStartTime . ' : ' . $workEndTime . '.'
//            ], 422);
//        }
//
//        $currentDate = date('Y-m-d'); // Today's date
//        $currentTimeStamp = time(); // Current timestamp
//        $minimumBookingTimeStamp = $currentTimeStamp + (30 * 60); // 30 minutes ahead
//
//        // Apply 30-minute rule **only if booking is for today**
//        if ($bookingDate == $currentDate && $bookingDateTime < $minimumBookingTimeStamp) {
//            return response()->json([
//                'status' => 'error',
//                'message' => translateWithHTMLTags('يجب أن يكون وقت الحجز على الأقل 30 دقيقة قبل وقت الخدمة'),
//            ], 422);
//        }
//
//
//        DB::beginTransaction();
//
//        try {
//            $order = Order::create([
//                'user_id' => $request->user()->id,
//                'product_id' => $service->id,
//                'order_status_id' => OrderStatus::select('id')->where('default_status', true)->first()->id,
//                'car_id' => $request->car_id,
//                'car_model' => $request->car_model,
//                'car_number' => $request->car_number,
//                'image' => $request->image,
//                'note' => $request->note,
//                'booking_date' => $request->booking_date,
//                'booking_time' => $request->booking_time,
//                'latitude' => $request->latitude,
//                'longitude' => $request->longitude,
//                'location' => $request->location,
//                'totalBeforeDiscount' => $orderTotalPrice,
//                'total_price' => $orderTotalPrice,
//                'payment_method' => $request->payment_method,
//                'captain_id' => null, // Assign captain
////                'captain_id' => null, // Assign captain
//            ]);
//
//
//            OrderItem::create([
//                'order_id' => $order->id,
//                'product_id' => $service->id,
//                'product_name' => $service->name,
//                'price' => $service->price,
//            ]);
//
//            if ($request->save_car_details == true) {
//                $user->cars()->syncWithoutDetaching([
//                    $request->car_id => [
//                        'car_model' => $request->car_model,
//                        'car_number' => $request->car_number
//                    ]
//                ]);
//            }
//
//            if ($request->save_address_details) {
//                // Check if the address already exists
//                $existingAddress = $user->addresses()->where([
//                    'latitude' => $request->latitude,
//                    'longitude' => $request->longitude,
//                    'address_title' => $request->location,
//                ])->first();
//
//                // Only save if the address doesn't exist
//                if (!$existingAddress) {
//                    $addressData = [
//                        'latitude' => $request->latitude,
//                        'longitude' => $request->longitude,
//                        'address_title' => $request->location,
//                        'user_id' => $user->id,
//                    ];
//
//                    $user->addresses()->create($addressData);
//                }
//            }
//
//
//            // Handle additional services (choices) if any
//            if ($request->has('choices')) {
//                $order->choices()->attach($request->choices);
//            }
//
//            // Handle order images
//            if ($request->hasFile('images')) {
//                foreach ($request->file('images') as $image) {
//                    $path = $image->store('uploads/order_images', 'public');
//                    $order->images()->create([
//                        'image' => $path
//                    ]);
//                }
//            }
//
//            if ($request->has('discount_code')) {
//                $discountResponse = $this->discountHandler->applyDiscount(
//                    $request->discount_code,
//                    $service_id,
//                    $orderTotalPrice,
//                    $user->id
//                );
//                if ($discountResponse['status'] === 'error') {
//                    return response()->json(['message' => $discountResponse['message']], 400);
//                } else {
//                    $order->update([
//                        'total_price' => $discountResponse['final_price'],
//                        'discount_applied' => $request->discount_code,
//                    ]);
//                }
//
//            }
//            $admins = Admin::all();
//
//            Notification::send($admins, new OrderCreatedNotification($order));
//            $validAdmins = $admins->filter(function ($admin) {
//                return filter_var($admin->email, FILTER_VALIDATE_EMAIL);
//            });
//
//            foreach ($validAdmins as $admin) {
//                try {
//                    Notification::route('mail', $admin->email)
//                        ->notify(new OrderCreatedEmailAdmin($order));
//                } catch (\Exception $e) {
//                }
//            }
//
//            DB::commit();
//
//            $paymentLink = route('user.payment', ['order_number' => $order->number, 'method' => $request->payment_method]);
//
//            return response()->json([
//                'status' => 'success',
//                'message' => __('general.Order_creation_success'),
//                'payment_url' => $paymentLink,
//                'discountAmount' => $discountResponse['discount_amount'] ?? '',
//                'final_price' => $discountResponse['final_price'] ?? intval($orderTotalPrice),
//            ], 201);
//
//        } catch (\Throwable $e) {
//            DB::rollBack();
//            return response()->json(['status' => 'faild', 'message' => __('general.Order_creation_failed'), 'error' => $e->getMessage()], 500);
//        }
//    }
//
//    public function checkout_with_package(CheckoutRequest $request)
//    {
//        // Validate request
//        $request->validated();
//
//        $user = $request->user();
//
//        try {
//            $userPackage = $this->checkOutservice->checkUserPackage($request, $user);
//            $package = Package::findOrFail($userPackage->package_id);
//            $orderTotalPrice = $this->checkOutservice->checkTotalPriceOrder($package, $request);
//            $this->checkOutservice->checkTimeReservation($request);
//            $availableCaptains = Captain::where('status', 'available')->where('is_active', 1)->get();
//            $captain = $availableCaptains->shift();
//
//        } catch (\Exception $e) {
//            return ApiResponse::sendResponse(400, $e->getMessage());
//        }
//
//        DB::beginTransaction();
//
//        try {
//            $order = $this->checkOutservice->createOrder($request, $package, $orderTotalPrice, $captain, $userPackage);
//            $invoicePath = $this->checkOutservice->generateInvoicePDF($order);
//            $order->update(['invoice_url' => $invoicePath]);
//
//            if ($request->save_car_details == true) {
//                $user->cars()->syncWithoutDetaching([
//                    $request->car_id => [
//                        'car_model' => $request->car_model,
//                        'car_number' => $request->car_number
//                    ]
//                ]);
//            }
//
//            if ($request->save_address_details) {
//                // Check if the address already exists
//                $existingAddress = $user->addresses()->where([
//                    'latitude' => $request->latitude,
//                    'longitude' => $request->longitude,
//                    'address_title' => $request->location,
//                ])->first();
//
//                // Only save if the address doesn't exist
//                if (!$existingAddress) {
//                    $addressData = [
//                        'latitude' => $request->latitude,
//                        'longitude' => $request->longitude,
//                        'address_title' => $request->location,
//                        'user_id' => $user->id,
//                    ];
//
//                    $user->addresses()->create($addressData);
//                }
//            }
//
//
//            $userPackage->decrement('remaining_washes');
//
//
//            $this->checkOutservice->sendNotificationToAdmin($order);
//
//            DB::commit();
//
//            return ApiResponse::sendResponse(201, __('general.Order_creation_success'));
//
//        } catch (\Throwable $e) {
//            DB::rollBack();
//            return response()->json(['status' => 'faild', 'message' => __('general.Order_creation_failed'), 'error' => $e->getMessage()], 500);
//        }
//    }


    public function checkCoupon(Request $request)
    {
        $user = $request->user();

        $discount = DiscountCode::where('code', $request->discount_code)
            ->where('status', 'active')
            ->where('number_of_used', '>', 0)
            ->first();

        if (!$discount) {
            return ApiResponse::sendResponse(200, __('general.This_discount_code_is_either_expired_or_invalid'));
        }

        $alreadyUsed = $discount->users()->where('user_id', $user->id)->exists();
        if ($alreadyUsed) {
            return ApiResponse::sendResponse(200, __('general.You_have_already_used_this_discount_code'));

        }
        $data = [
            "id" => 3,
            "code" => $discount->code,
            "price" => $discount->price,
            "discount_type" => $discount->discount_type,
        ];
        return ApiResponse::sendResponse(200, 'success', $data);

    }

    public function applyCoupon(Request $request, $orderId)
    {
        $user = $request->user();
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        // Ensure the order hasn't been paid
        if ($order->payment_status == 'paid') {
            return response()->json(['message' => 'Coupon cannot be applied after payment.'], 400);
        }

        $discountResponse = $this->discountHandler->applyDiscount(
            $request->discount_code,
            $order->service_id,
            $order->totalBeforeDiscount,
            $user->id
        );

        if ($discountResponse['status'] === 'error') {
            return ApiResponse::sendResponse(400, $discountResponse['message'], []);
        }

        $order->update([
            'total_price' => number_format($discountResponse['final_price'], 2, '.', ''),
            'discount_applied' => $request->discount_code,
        ]);

        $data = [
            'total_price' => $discountResponse['final_price'],
        ];
        return ApiResponse::sendResponse(200, $discountResponse['message'], $data);

    }

    public function cancelCoupon(Request $request, $orderId)
    {
        $user = $request->user();
        $order = Order::findOrFail($orderId);

        // Check if the user owns the order
        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        // Ensure the order hasn't been paid
        if ($order->payment_method !== null) {
            return response()->json(['message' => 'Coupon cannot be canceled after payment.'], 400);
        }

        // Check if a coupon was applied
        if ($order->discount_applied === null) {
            return response()->json(['message' => 'No coupon is applied to this order.'], 400);
        }

        // Retrieve the discount code
        $discountCode = $order->discount_applied;
        $discount = DiscountCode::where('code', $discountCode)->first();

        if (!$discount) {
            return response()->json(['message' => 'Invalid discount code.'], 400);
        }

        // Revert the discount application
        $order->update([
            'total_price' => $order->totalBeforeDiscount, // Assume you have this column
            'discount_applied' => null, // Clear the applied coupon
        ]);

        // Increment the number_of_used for the discount
        $discount->increment('number_of_used');

        // Remove the user's association with the discount
        $discount->users()->detach($user->id);

        $data = [
            'total_price' => $order->totalBeforeDiscount,
        ];
        return ApiResponse::sendResponse(200, "Coupon has been canceled, and the total price has been reverted.", $data);


    }


    public function payOrder(Request $request)
    {
        // Validate the input
        $request->validate([
            'ordernumber' => 'required|exists:orders,number',
            'payment_method' => 'required|in:creditcard,mada,applepay', // Add other payment methods if needed
        ]);

        // Fetch the order
        $order = Order::where('number', $request->ordernumber)->first();

        // Check if the order has already been paid
        if ($order->payment_status === 'paid') {
            return response()->json([
                'status' => 'error',
                'message' => __('general.This_order_has_already_been_paid'),
            ], 400);
        }

        // Combine the booking date and booking time into a single datetime
        $bookingDateTime = $order->booking_date . ' ' . $order->booking_time;
        $bookingDateTime = \Carbon\Carbon::parse($bookingDateTime);

        // Check if the booking date and time is before the current time
        if ($bookingDateTime->isBefore(now())) {
            return response()->json([
                'status' => 'error',
                'message' => __('general.The_booking_time_has_expired')
            ], 400);
        }

        // Generate the payment link
        $paymentLink = route('user.payment', [
            'order_number' => $order->number,
            'method' => $request->payment_method,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => __('general.Payment_link_generated'),
            'payment_url' => $paymentLink,
        ], 201);
    }

    private function convertTimeToMinutes($duration)
    {
        list($hours, $minutes) = explode(':', $duration);
        return ($hours * 60) + $minutes;
    }
}