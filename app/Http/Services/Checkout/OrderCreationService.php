<?php

namespace App\Http\Services\Checkout;

use App\Http\Services\DiscountHandler;
use App\Models\Captain;
use App\Models\Choice;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Package;
use App\Models\Service;
use App\Models\Setting;
use App\Models\UserPackage;
use Illuminate\Support\Facades\Log;

/**
 * Service for creating orders
 */
class OrderCreationService
{
    protected DiscountHandler $discountHandler;

    public function __construct(DiscountHandler $discountHandler)
    {
        $this->discountHandler = $discountHandler;
    }

    /**
     * Calculate total price including additional services
     */
    public function calculateTotalPrice($serviceOrPackage, $request): float
    {
        $basePrice = $serviceOrPackage->price;
        $additionalPrice = 0;

        if ($request->has('choices') && is_array($request->choices)) {
            $choices = Choice::whereIn('id', array_filter($request->choices))->get();
            $additionalPrice = $choices->sum('service_price');
        }

        return $basePrice + $additionalPrice;
    }

    /**
     * Validate booking time
     */
    public function validateBookingTime($request): void
    {
        $setting = Setting::first();
        $workStartTime = $setting->working_strat_time;
        $workEndTime = $setting->working_end_time;

        $workStartTimeStamp = strtotime($workStartTime);
        $workEndTimeStamp = strtotime($workEndTime);
        $bookingTimeStamp = strtotime($request->booking_time);

        if ($bookingTimeStamp < $workStartTimeStamp || $bookingTimeStamp > $workEndTimeStamp) {
            throw new \Exception(
                translateWithHTMLTags('وقت الحجز غير صالح. يجب أن يكون من ') .
                    $workStartTime . ' : ' . $workEndTime . '.',
                422
            );
        }

        // 30-minute rule for today's bookings
        $bookingDate = $request->booking_date;
        $currentDate = date('Y-m-d');
        $bookingDateTime = strtotime($bookingDate . ' ' . $request->booking_time);
        $minimumBookingTimeStamp = time() + (30 * 60);

        if ($bookingDate == $currentDate && $bookingDateTime < $minimumBookingTimeStamp) {
            throw new \Exception(
                translateWithHTMLTags('يجب أن يكون وقت الحجز على الأقل 30 دقيقة قبل وقت الخدمة'),
                422
            );
        }
    }

    /**
     * Create order from package
     */
    public function createPackageOrder(
        $request,
        Package $package,
        float $totalPrice,
        ?Captain $captain,
        UserPackage $userPackage,
        $user
    ): Order {
        $defaultStatus = $this->getDefaultOrderStatus();

        $order = Order::create([
            'user_id' => $user->id,
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
            'address' => $request->location,
            'totalBeforeDiscount' => $totalPrice,
            'total_price' => $totalPrice,
            'payment_method' => 'package',
            'payment_status' => 'paid',
            'captain_id' => $captain->id ?? null,
            'user_package_id' => $userPackage->id,
        ]);

        $this->attachChoices($order, $request);
        $this->attachImages($order, $request);

        return $order;
    }

    /**
     * Create order from service
     */
    public function createServiceOrder(
        $request,
        Service $service,
        float $totalPrice,
        $user
    ): Order {
        $defaultStatus = $this->getDefaultOrderStatus();

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
            'totalBeforeDiscount' => $totalPrice,
            'total_price' => $totalPrice,
            'payment_method' => $request->payment_method,
            'captain_id' => null,
        ]);

        // Apply discount if provided
        if ($request->has('discount_code')) {
            $this->applyDiscount($order, $request, $service->id, $totalPrice, $user->id);
        }

        $this->attachChoices($order, $request);
        $this->attachImages($order, $request);

        return $order;
    }

    /**
     * Get default order status
     */
    protected function getDefaultOrderStatus(): OrderStatus
    {
        $defaultStatus = OrderStatus::where('default_status', true)->first();

        if (!$defaultStatus) {
            $defaultStatus = OrderStatus::first();
            if (!$defaultStatus) {
                throw new \Exception('لا توجد حالات طلب متاحة في النظام', 500);
            }
        }

        return $defaultStatus;
    }

    /**
     * Attach choices to order
     */
    protected function attachChoices(Order $order, $request): void
    {
        if ($request->has('choices') && is_array($request->choices)) {
            $validChoiceIds = Choice::whereIn('id', array_filter($request->choices))
                ->pluck('id')
                ->toArray();

            if (!empty($validChoiceIds)) {
                $order->choices()->attach($validChoiceIds);
            }
        }
    }

    /**
     * Attach images to order
     */
    protected function attachImages(Order $order, $request): void
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('uploads/order_images', 'public');
                $order->images()->create(['image' => $path]);
            }
        }
    }

    /**
     * Apply discount to order
     */
    protected function applyDiscount(Order $order, $request, $serviceId, $totalPrice, $userId): void
    {
        $discountResponse = $this->discountHandler->applyDiscount(
            $request->discount_code,
            $serviceId,
            $totalPrice,
            $userId
        );

        if ($discountResponse['status'] === 'error') {
            throw new \Exception($discountResponse['message'], 400);
        }

        $order->update([
            'total_price' => $discountResponse['final_price'],
            'discount_applied' => $request->discount_code,
        ]);
    }
}
