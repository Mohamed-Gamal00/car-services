<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Rating;
use App\Notifications\RatingCreatedNotification;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);
        $order = Order::findOrFail($validated['order_id']);
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            return ApiResponse::sendResponse(403, 'Unauthorized.');
        }

        // Ensure the order is completed
        if ($order->order_status_id !== 4) {
            return ApiResponse::sendResponse(400, __('general.Cannot_rate_an_incomplete_order'));

        }

        // Check if a rating already exists for this order
        $existingRating = Rating::select('stars')->where('order_id', $order->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingRating) {
            return ApiResponse::sendResponse(201, __('general.You_have_already_rated_this_order'), $existingRating);
        }


        $rating = Rating::create([
            'user_id' => auth()->id(),
            'captain_id' => $order->captain_id,
            'order_id' => $order->id,
            'stars' => $validated['stars'],
            'comment' => $validated['comment'],
        ]);

        // Notify all admins about the new rating
        $admins = Admin::all();
        foreach ($admins as $admin) {
            $admin->notify(new RatingCreatedNotification($rating));
        }
        return ApiResponse::sendResponse(200, __('general.Rating_submitted_successfully'));
    }
}
