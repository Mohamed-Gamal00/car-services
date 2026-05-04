<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use App\Notifications\CaptainNotifyArrival;
use App\Notifications\CompleteOrder;
use App\Notifications\OrderCreatedEmailAdmin;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CaptainOrderController extends Controller
{
    use Helper;

    public function mainOrders(Request $request)
    {
        $user = $request->user();
        $orders = Order::latest()
            ->with('products', 'orderStatus','images', 'choices','userPackage.package')
            ->where('captain_id', $user->id)
            ->get();

        $completed_orders = Order::latest()
            ->with('products', 'orderStatus', 'choices')
            ->where('captain_id', $user->id)
            ->where('order_status_id', 4)
            ->count();
        $data = [
            'completed_orders' => $completed_orders,
            'orders' => OrderResource::collection($orders),
        ];

        if ($data) {
            return ApiResponse::sendResponse(200, 'success', $data);
        } else {
            return ApiResponse::sendResponse(200, 'لا يوجد طلبات لعرضها');

        }

    }

    public function CompleteOrders(Request $request)
    {
        $user = $request->user();

        // Fetch all orders for the captain with specific conditions
        $returend_orders = Order::latest()
            ->with('products', 'orderStatus', 'choices') // Include related data
            ->where('captain_id', $user->id)
            ->get();

        $completed_orders = Order::latest()
            ->with('products', 'orderStatus', 'choices') // Include related data
            ->where('captain_id', $user->id)
            ->where('order_status_id', 4) // Filter by status_id = 4
            ->get();

        $allOrders = $returend_orders->merge($completed_orders);


        // Prepare the response data
        $data = [
            'orders' => OrderResource::collection($allOrders),
        ];

        // Return response based on data availability
        if ($data['orders']->isNotEmpty()) {
            return ApiResponse::sendResponse(200, 'success', $data);
        } else {
            return ApiResponse::sendResponse(200, 'لا يوجد طلبات لعرضها');
        }
    }


    public function showOrder(Request $request, $number)
    {
        $user = $request->user();
//        return $user;
        $number = $request->header('number');
//        return $number;
        if (!$number) {
            return response()->json([
                'message' => translateWithHTMLTags('Order number is required in the header'),
                'errors' => [
                    'number' => ['Order number is required.']
                ]
            ], 400); // Bad Request
        }


        $order = Order::latest()
            ->with('products', 'orderStatus', 'choices', 'user','userPackage.package')
            ->where('captain_id', $user->id)
            ->where('number', $number)
            ->first();

//        return $order->user->first_name;

        if (!$order) {
            return ApiResponse::sendResponse(200, __('general.Order_not_found'));
        }
//        return $order;
        $data = [
            'order' => new  OrderResource($order),
            'order status' => $order->orderStatus->getCurrentNameLangAttribute(),
        ];
        return ApiResponse::sendResponse(200, 'Data Retrieved Successfully', $data);
    }

    public function acceptOrder(Request $request, $order_id)
    {
        $captain = $request->user();
        $order = Order::where('captain_id', $captain->id)->findOrFail($order_id);
//        $order_status = OrderStatus::where('default_status', true)->first()->id;
        if ($order->order_status_id != OrderStatus::where('default_status', true)->first()->id) {
            return response()->json(['message' => 'Order cannot be accepted.'], 400);
        }
//        return $order->order_status_id;
        $order->update([
            'order_status_id' => OrderStatus::where('name_en', 'accepted')->first()->id,
        ]);

        // Mark captain as busy
        $captain->update([
            'status' => 'busy',
        ]);

        return response()->json(['message' => __('general.Order_accepted')]);
    }

    public function completeOrder(Request $request, $order_id)
    {
        $captain = $request->user(); // Assuming captain is authenticated

        // Find the order assigned to this captain and ensure it exists
        $order = Order::where('id', $order_id)
            ->where('captain_id', $captain->id)
            ->first();

        if (!$order) {
            return response()->json([

                'message' => translateWithHTMLTags('هذا الكابتن لا يتواجد في هذا الطلب'),
            ], 404);
        }

//        // Update the order status to 'completed'
        $order->update(['order_status_id' => 4]);

        // Notify admins about the completed order
//        $admins = Admin::all();
        $admins = Admin::whereNotIn('id', [13, 14, 15])->get();
        Notification::send($admins, new CompleteOrder($order));

        $validAdmins = $admins->filter(function ($admin) {
            return filter_var($admin->email, FILTER_VALIDATE_EMAIL);
        });

        foreach ($validAdmins as $admin) {
            try {
                Notification::route('mail', $admin->email)
                    ->notify(new CompleteOrder($order));
            } catch (\Exception $e) {
            }
        }

        $client = User::findOrFail($order->user_id);
        app()->setLocale($client->lang ?? 'ar');
        // Mark the captain as available
        $captain->update(['status' => 'available']);

        $client = User::FindOrFail($order->user_id);
        $deviceTokens = $client->devicetokens()->pluck('token');
        $title = __('general.The_car_has_been_cleaned');
        $body = __('general.Cleaning_is_done');
        $tokens = $deviceTokens;
        $data = [
            'order_id' => 'order',
        ];

        $this->notifyByFirebase($title, $body, $tokens, $data);

        return response()->json(['message' => __('general.The_car_has_been_cleaned')]);
    }


}
