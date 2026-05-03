<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductsResource;
use App\Http\Resources\ViewServiceResource;
use App\Models\Captain;
use App\Models\Guest;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
{
    // all services
    public function getServices()
    {

        $services = Service::where('is_active', 1)->get();

        if (count($services) > 0) {
            return ApiResponse::sendResponse(200, ' services Retrieved Successfully', ViewServiceResource::collection($services));
        }
        return ApiResponse::sendResponse(200, 'No services To Retrieved', []);
    }


    //service details
    public function getService_id($service_id)
    {
        $service = Service::findOrFail($service_id);
        if ($service) {
            return ApiResponse::sendResponse(200, 'Service Retrieved Successfully', new ViewServiceResource($service));
        }
        return ApiResponse::sendResponse(200, 'No service To Retrieved', []);
    }

    public function getSpecificServiceTimes(Request $request, $service_id = null)
    {
        $BookinDate = $request->input('booking_date', now()->format('Y-m-d'));
        $isUsingPackage = $request->has('user_package_id') && !empty($request->user_package_id);
        if ($isUsingPackage) {
            // تحديد مدة زمنية ثابتة للباقة مثلاً 1 ساعة ونص
            $package = Package::findOrFail($request->user_package_id);
            $serviceDuration = $package->duration ?? '01:00';
        } else {
            // لو مش باقة، يبقى لازم يكون فيه service_id
            if (!$service_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'يجب تحديد service_id أو user_package_id'
                ], 422);
            }

            $service = Service::findOrFail($service_id);
            $serviceDuration = $service->duration;
        }

        $dates = $this->generateCurrentDay($BookinDate);
        $timeSlotsByDate = [];
        $allReservedTimes = $this->getAllReservedTimesForDate($dates);

        foreach ($dates as $date) {
            if ($isUsingPackage) {
                // لو باقة، هتعتمد على كل الأوقات المحجوزة في اليوم ده
                $reservedTimes = $allReservedTimes[$date] ?? [];
            } else {
                // لو خدمة، هتجيب المحجوزين لنفس الخدمة + محجوزين في اليوم كله (عشان الكابتن ميكونش محجوز في خدمة تانية)
                $reservedTimes = array_merge(
                    $this->getReservedTimeSlotsForService($date, $service_id),
                    $allReservedTimes[$date] ?? []
                );
            }

            $setting = Setting::first();
            $timeSlots = $this->generateTimeSlotsWithStatus(
                $setting->working_strat_time,
                $setting->working_end_time,
                $serviceDuration,
                $reservedTimes,
                $BookinDate,
                $setting->start_rest_time,
                $setting->end_rest_time
            );

            $timeSlotsByDate[] = [
                'date' => $date,
                'timeSlots' => $timeSlots
            ];
        }

        return response()->json([
            'status' => 'success',
            'timeSlotsByDate' => $timeSlotsByDate
        ]);
    }


//    public function getSpecificServiceTimes($service_id, Request $request) /* main function الفنكشن الاساسية */
//    {
//        // Retrieve the service
//        $service = Product::findOrFail($service_id);
//        // Use the provided booking date or default to the current date
//        $BookinDate = $request->input('booking_date', now()->format('Y-m-d'));
////        return $BookinDate;
//
//        // Calculate the times days starting from the booking date
//        $dates = $this->generateCurrentDay($BookinDate); // بجيب التواريخ بناء علي التاريخ اللي اللي هييجي من startBookinDate
//
//        // Initialize an array to hold the time slots for each date
//        $timeSlotsByDate = []; // ارراي هحط فيه الوقات بتاع اليوم
//
//        $allReservedTimes = $this->getAllReservedTimesForDate($dates);
//
//        // Loop through each date and generate the time slots for that day
//        foreach ($dates as $date) {
//            // Fetch reserved time slots for the selected day
//            $reservedTimes = $this->getReservedTimeSlotsForService($date, $service_id);
//            $reservedTimes = array_merge($reservedTimes, $allReservedTimes[$date] ?? []);
//
//            $setting = Setting::first();
//            $workStartTime = $setting->working_strat_time;
//            $workEndTime = $setting->working_end_time;
//            $restStartTime = $setting->start_rest_time;
//            $restEndTime = $setting->end_rest_time;
//
//            // Define the working hours and slot duration (e.g., 60 minutes)
//            $timeSlots = $this->generateTimeSlotsWithStatus($workStartTime, $workEndTime, $service->duration, $reservedTimes, $BookinDate, $restStartTime, $restEndTime);
//
//            // Add the date and its time slots to the array
//            $timeSlotsByDate[] = [
//                'date' => $date,
//                'timeSlots' => $timeSlots
//            ];
//        }
//
//        // Return the time slots by date in the desired format
//        return response()->json([
//            'status' => 'success',
//            'timeSlotsByDate' => $timeSlotsByDate
//        ]);
//    }

    private function generateCurrentDay($BookinDate) // بجيب الاوقات بتاعت يوم واحد
    {
        $dates = [];
        $start = strtotime($BookinDate);

        for ($i = 0; $i < 1; $i++) {
            $dates[] = date('Y-m-d', strtotime("+$i days", $start));
        }

        return $dates;
    }

    private function getAllReservedTimesForDate($dates)
    {
        $allReservedTimes = [];
        // Loop through each date to find all reserved times across all services
        foreach ($dates as $date) {
            $reservedTimes = Order::where('booking_date', $date)
                ->get()
                ->pluck('booking_time')
                ->map(function ($time) {
                    return date('H:i', strtotime($time));
                })
                ->toArray();

            // Store the reserved times for this date
            $allReservedTimes[$date] = $reservedTimes;
        }

        return $allReservedTimes;
    }/*new*/

    private function getReservedTimeSlotsForService($date, $service_id) /* بجيب الأوقات المحجوزة لخدمة معينة في يوم معين  */
    {
        // Fetch all reservations for the given date and service, format booking_time to 'H:i'
        $reservedTimes = Order::where('booking_date', $date)
            ->where('service_id', $service_id)
            ->get()
            ->pluck('booking_time')
            ->map(function ($time) {
                return date('H:i', strtotime($time));  // Format to 'H:i' to match the time slot format
            })
            ->toArray();

        return $reservedTimes;
    }

    private function generateTimeSlotsWithStatus($startTime, $endTime, $serviceDuration, $reservedTimes = [], $BookinDate, $restStartTime = null, $restEndTime = null)
    {
        $timeSlots = [];
        $start = strtotime($startTime);   // workStartTime
        $end = strtotime($endTime);      //  workEndTime

        $startRest = strtotime($restStartTime);   // rest Time
        $endRest = strtotime($restEndTime);      //  rest Time

        $currentTimestamp = time() + (30 * 60);
        $isToday = (date('Y-m-d') === $BookinDate);


//        return $isToday;

        while ($start < $end) {
            $timeSlot = date("H:i", $start); // 09:00
            // Skip past time slots if the date is today
            if ($isToday && $start < $currentTimestamp) {
                $start = strtotime("+{$this->convertTimeToMinutes($serviceDuration)} minutes", $start);
                continue;
            }

            // تجاهل الأوقات اللي بتقع داخل وقت الراحة
            if (
                $startRest !== null && $endRest !== null &&
                $start >= $startRest && $start < $endRest
            ) {
                $start = strtotime("+{$this->convertTimeToMinutes($serviceDuration)} minutes", $start);
                continue;
            }

            // Check if this time slot is reserved
            $isReserved = in_array($timeSlot, $reservedTimes);


            // Count available captains
            $availableCaptains = Captain::where('status', 'available')->where('is_active', 1)
                ->whereNotIn('id', function ($query) use ($timeSlot, $BookinDate) {
                    $query->select('captain_id')
                        ->from('orders')
                        ->where('payment_status', 'paid')
                        ->where('booking_time', $timeSlot)
                        ->where('booking_date', $BookinDate);
                })
                ->count();


            // Determine the status
            if ($isReserved && $availableCaptains == 0) {
//                $timeSlots[] = [
//                    'time' => $timeSlot,
//                    'status' => 'reserved',
//                    'availableCaptains' => $availableCaptains,
//                ];
            } else {
                $timeSlots[] = [
                    'time' => $timeSlot,
                    'status' => 'available',
                    'availableCaptains' => $availableCaptains,
                ];
            }

            // Move to the next time slot
            $start = strtotime("+{$this->convertTimeToMinutes($serviceDuration)} minutes", $start);
        }

        return $timeSlots;
    }

    function convertTimeToMinutes($time)
    {
        // Split the time by colon to get hours and minutes
        list($hours, $minutes) = explode(':', $time);

        // Convert the time to minutes
        return ($hours * 60) + $minutes;
    }


}
