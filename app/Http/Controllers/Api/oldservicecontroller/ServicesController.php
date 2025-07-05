<?php

namespace App\Http\Controllers\Api\oldservicecontroller;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductsResource;
use App\Http\Resources\ViewServiceResource;
use App\Models\Captain;
use App\Models\Guest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
{
    public function getServices()
    {

        $services = Product::all();

        if (count($services) > 0) {
            return ApiResponse::sendResponse(200, ' services Retrieved Successfully', ViewServiceResource::collection($services));
        }
        return ApiResponse::sendResponse(200, 'No services To Retrieved', []);
    }


    public function getService_id($service_id)
    {
        $service = Product::findOrFail($service_id);
        if ($service) {
            return ApiResponse::sendResponse(200, 'Service Retrieved Successfully', new ViewServiceResource($service));
        }
        return ApiResponse::sendResponse(200, 'No service To Retrieved', []);
    }


    public function checkTimeReserved($service_id, $time, Request $request)
    {
        // Retrieve service details
        $service = Product::findOrFail($service_id);

        // Use the provided booking date or default to the current date
        $bookingDate = $request->input('booking_date', now()->format('Y-m-d'));

        // Retrieve working hours from settings
        $setting = Setting::first();
        $workStartTime = strtotime("$bookingDate {$setting->working_strat_time}");
        $workEndTime = strtotime("$bookingDate {$setting->working_end_time}");

        // Convert requested time to timestamp
        $requestedStart = strtotime("$bookingDate $time");
        $serviceDuration = $this->convertTimeToMinutes($service->duration);
        $requestedEnd = strtotime("+$serviceDuration minutes", $requestedStart);

        // Check if the requested time is within working hours
        if ($requestedStart < $workStartTime || $requestedEnd > $workEndTime) {
            return response()->json([
                'status' => 'out_of_working_hours',
                'message' => translateWithHTMLTags('The selected time is outside of working hours.')
            ], 200);
        }

        // Fetch reserved time slots for the date and service
        $reservedTimes = Order::where('booking_date', $bookingDate)
            ->where('product_id', $service_id)
            ->get();

        // Check for overlapping reservations
        foreach ($reservedTimes as $order) {
            $reservedStart = strtotime("$bookingDate {$order->booking_time}");
            $reservedEnd = strtotime("+$serviceDuration minutes", $reservedStart);

            // Check if the requested time overlaps with any reserved time
            if ($requestedStart < $reservedEnd && $requestedEnd > $reservedStart) {
                return response()->json([
                    'status' => 'reserved',
                    'message' => translateWithHTMLTags('The selected time overlaps with an existing reservation.')
                ], 200);
            }
        }

        return response()->json([
            'status' => 'available',
            'message' => translateWithHTMLTags('The selected time is available.')
        ], 200);
    }

    function convertTimeToMinutes($time)
    {
        // Split the time by colon to get hours and minutes
        list($hours, $minutes) = explode(':', $time);

        // Convert the time to minutes
        return ($hours * 60) + $minutes;
    }

// Helper function to generate the next 30 days starting from a given date

    public function getServiceTimes($service_id, Request $request) /* main function الفنكشن الاساسية */
    {
        // Retrieve the service
        $service = Product::findOrFail($service_id);

        // Use the provided booking date or default to the current date
        $startDate = $request->input('booking_date', now()->format('Y-m-d'));
        // Calculate the next 30 days starting from the booking date
        $dates = $this->generateNext30Days($startDate);

        // Initialize an array to hold the time slots for each date
        $timeSlotsByDate = [];

        // Loop through each date and generate the time slots for that day
        foreach ($dates as $date) {
            // Fetch reserved time slots for the selected day
            $reservedTimes = $this->getReservedTimeSlotsForService($date, $service_id);
            $setting = Setting::first();
            $workStartTime = $setting->working_strat_time;
            $workEndTime = $setting->working_end_time;

            // Define the working hours and slot duration (e.g., 60 minutes)
            $timeSlots = $this->generateTimeSlotsWithStatus($workStartTime, $workEndTime, $service->duration, $reservedTimes);

            // Add the date and its time slots to the array
            $timeSlotsByDate[] = [
                'date' => $date,
                'timeSlots' => $timeSlots
            ];
        }

        // Return the time slots by date in the desired format
        return response()->json([
            'status' => 'success',
            'timeSlotsByDate' => $timeSlotsByDate
        ]);
    }

    private function generateNext30Days($startDate)
    {
        $dates = [];
        $start = strtotime($startDate);

        for ($i = 0; $i < 30; $i++) {
            $dates[] = date('Y-m-d', strtotime("+$i days", $start));
        }

        return $dates;
    }

    private function getReservedTimeSlotsForService($date, $service_id) /* بجيب الأوقات المحجوزة لخدمة معينة في يوم معين  */
    {
        // Fetch all reservations for the given date and service, format booking_time to 'H:i'
        $reservedTimes = Order::where('booking_date', $date)
            ->where('product_id', $service_id)
            ->get()
            ->pluck('booking_time')
            ->map(function ($time) {
                return date('H:i', strtotime($time));  // Format to 'H:i' to match the time slot format
            })
            ->toArray();

        return $reservedTimes;
    }

// Helper function to get reserved time slots for a specific service on a given date

    private function generateTimeSlotsWithStatus($startTime, $endTime, $serviceDuration, $reservedTimes = [])
    {
        $timeSlots = [];
        $start = strtotime($startTime);
        $end = strtotime($endTime);

        while ($start < $end) {
            $timeSlot = date("H:i", $start);

            // Check if this time slot is reserved
            $isReserved = in_array($timeSlot, $reservedTimes);

            // Count available captains
            $availableCaptains = Captain::where('status', 'available')->where('is_active', 1)
                ->whereNotIn('id', function ($query) use ($timeSlot) {
                    $query->select('captain_id')
                        ->from('orders')
                        ->where('booking_time', $timeSlot)
                        ->where('booking_date', now()->format('Y-m-d'));
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

    /*old code*/
//    private function generateTimeSlotsWithStatus($startTime, $endTime, $serviceDuration, $reservedTimes = [])
//    {
//        $timeSlots = [];
//        $start = strtotime($startTime);
//        $end = strtotime($endTime);
//
//        while ($start < $end) {
//            $timeSlot = date("H:i", $start);
//
//            // Check if the time slot is reserved
//            if (in_array($timeSlot, $reservedTimes)) {
////                $timeSlots[] = [
////                    'time' => $timeSlot,
////                    'status' => 'reserved'
////                ];
//            } else {
//                $timeSlots[] = [
//                    'time' => $timeSlot,
//                    'status' => 'available'
//                ];
//            }
//
//            // Move to the next time slot
//            $start = strtotime("+{$this->convertTimeToMinutes($serviceDuration)} minutes", $start);
//        }
//
//        return $timeSlots;
//    }

// Generate time slots with their statuses (reserved or available)

    public function getSpecificServiceTimes($service_id, Request $request) /* main function الفنكشن الاساسية */
    {
        // Retrieve the service
        $service = Product::findOrFail($service_id);

        // Use the provided booking date or default to the current date
        $startDate = $request->input('booking_date', now()->format('Y-m-d'));
        // Calculate the next 30 days starting from the booking date
        $dates = $this->generateCurrentDay($startDate);

        // Initialize an array to hold the time slots for each date
        $timeSlotsByDate = [];

        // Loop through each date and generate the time slots for that day
        foreach ($dates as $date) {
            // Fetch reserved time slots for the selected day
            $reservedTimes = $this->getReservedTimeSlotsForService($date, $service_id);
            $setting = Setting::first();
            $workStartTime = $setting->working_strat_time;
            $workEndTime = $setting->working_end_time;

            // Define the working hours and slot duration (e.g., 60 minutes)
            $timeSlots = $this->generateTimeSlotsWithStatus($workStartTime, $workEndTime, $service->duration, $reservedTimes);

            // Add the date and its time slots to the array
            $timeSlotsByDate[] = [
                'date' => $date,
                'timeSlots' => $timeSlots
            ];
        }

        // Return the time slots by date in the desired format
        return response()->json([
            'status' => 'success',
            'timeSlotsByDate' => $timeSlotsByDate
        ]);
    }

    private function generateCurrentDay($startDate)
    {
        $dates = [];
        $start = strtotime($startDate);

        for ($i = 0; $i < 1; $i++) {
            $dates[] = date('Y-m-d', strtotime("+$i days", $start));
        }

        return $dates;
    }


}
