<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductsResource;
use App\Models\Captain;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\User;
use App\Notifications\CaptainAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class UserOrdersController extends Controller
{
    use Helper;

    public function mainOrders(Request $request)
    {
        $user = $request->user();
        $orders = Order::latest()
            ->with('products', 'orderStatus', 'choices') // Ensure relationships are loaded
            ->where('user_id', $user->id)
            ->get();
//        return $orders;

        if ($orders) {
            return ApiResponse::sendResponse(200, 'success', OrderResource::collection($orders));
        } else {
            return ApiResponse::sendResponse(200, 'لا يوجد طلبات لعرضها');

        }

    }


    public function showOrder(Request $request)
    {
        $user = $request->user();
        $number = $request->header('number');
        if (!$number) {
            return response()->json([
                'message' => 'Order number is required in the header',
                'errors' => [
                    'number' => ['Order number is required.']
                ]
            ], 400); // Bad Request
        }


        $order = Order::latest()
            ->with('products', 'orderStatus', 'orderItems', 'orderItems.product')
            ->where('user_id', $user->id)
            ->where('return_order', false)
            ->where('number', $number)
            ->first();

        if (!$order) {
            return ApiResponse::sendResponse(200, 'الطلب غير موجود');
        }
//        return $order->car;
//        $filePath = $this->generateInvoicePDF($order);

        $data = [
            'order' => new  OrderResource($order),
            'order status' => $order->orderStatus->getCurrentNameLangAttribute(),
//            'invoice_url' => asset("storage/" . $filePath), // Add the URL here

        ];
        return ApiResponse::sendResponse(200, '', $data);
    }


//    public function destroy(Request $request)
//    {
//        $request->validate([
//            'product_id' => 'required|exists:order_items,product_id',
//            'order_number' => 'required|string|exists:orders,number',
//        ]);
//
//        $order = Order::where('number', $request->order_number)->firstOrFail();
//        $orderItem = $order->orderItems()->where('product_id', $request->product_id)->firstOrFail();
//
//        if ($orderItem->quantity > 1) {
//            $orderItem->decrement('quantity', 1);
//        } else {
//            $orderItem->delete();
//        }
//
//        // Recalculate the totals after the deletion
//        $newTotalBeforeDiscount = $order->orderItems->sum(function ($item) {
//            return $item->quantity * $item->product->price;
//        });
//
//        $newTotalPrice = $order->orderItems->sum(function ($item) {
//            return $item->quantity * ($item->discounted_price ?? $item->product->price);
//        });
//
//        // Update the order with the new totals
//        $order->update([
//            'totalBeforeDiscount' => $newTotalBeforeDiscount,
//            'total_price' => $newTotalPrice,
//        ]);
//        // Check if the order has no remaining items and delete it if necessary
//        if (!$order->orderItems()->exists()) {
//            $order->delete();
//        }
//        return ApiResponse::sendResponse(200, 'تم الحذف بنجاح');
//    }


    /*  الطلبات الملغية  */
    public function returns(Request $request)
    {
        $user = $request->user();

        // Fetch user
        $userWithRelations = User::with([
            'returnProducts',
            'orders.products'
        ])->where('id', $user->id)->first();

        if (!$userWithRelations) {
            return response()->json(['message' => 'User or related data not found'], 404);
        }

        // Fetch returned orders
        $returnedOrders = $userWithRelations->orders()
            ->where('return_order', true)
            ->get();

        // Fetch orders where order_status_id = 4
        $filteredOrders = $userWithRelations->orders()
            ->where('order_status_id', 4)
            ->get();

        // Combine both sets of orders
        $allOrders = $returnedOrders->merge($filteredOrders);

        if ($allOrders->isEmpty()) {
            return ApiResponse::sendResponse(200, 'لا يوجد مرتجعات أو طلبات بالحالة المطلوبة');
        }

        // Return JSON response
        return response()->json([
            'status_code' => 200,
            'message' => 'User return products retrieved successfully',
            'data' => [
                'products' => OrderResource::collection($allOrders), // Assuming OrderResource is used for orders
            ]
        ]);
    }

    public function NonRatingOrder(Request $request)
    {
        $user = $request->user();

        $latestOrder = $user->orders()
            ->whereDoesntHave('rating') // Ensure no rating exists
            ->where('rating_skipped', false) // Exclude skipped orders
            ->where('order_status_id', 4)
            ->latest() // Order by the most recent created_at timestamp
            ->first(); // Get only the latest one

        // Return JSON response
        return response()->json([
            'status_code' => 200,
            'message' => __('general.Data_retrieved_successfully'),
            'data' => [
                'product' => $latestOrder ? new OrderResource($latestOrder) : null, // Use OrderResource or return null if no order
            ]
        ]);
    }


    public function skipRating(Request $request, $ordernumber)
    {
        $user = $request->user();

        $order = $user->orders()->where('number', $ordernumber)->first();

        if (!$order) {
            return response()->json([
                'status_code' => 404,
                'message' => __('general.Order_not_found'),
            ]);
        }

        $order->update(['rating_skipped' => true]);

        return response()->json([
            'status_code' => 200,
            'message' => __('general.Order_rating_skipped_successfully'),
        ]);
    }


    /*  ### cancel order ###  */
    public function cancelOrder(Request $request)
    {

        $statusId = OrderStatus::where('id', 11)->first();
//        return $statusId;

        if (!$statusId) {
            return response()->json(['status_code' => 400, 'message' => 'Default order status not found'], 400);
        }

        $request->validate([
            'order_id' => 'required',
        ]);

        // If return_order_id is provided, update the order to mark it as a return order
        if ($request->order_id) {
            $order = Order::where('id', $request->order_id)->first();
            if (!$order) {
                return response()->json(['status_code' => 404, 'message' => 'Order not found'], 404);
            }

            // ✅ Handle return of wash for package orders
            if ($order->payment_method === 'package' && $order->user_package_id) {

                $userPackage = $order->userPackage;
                if ($userPackage) {
                    $userPackage->increment('remaining_washes');
                }
            }


//            return $order->captain_id;
            if ($order->captain_id) {
                $captain = Captain::where('id', $order->captain_id)->first();

                if ($captain) {
                    $captain->update([
                        'status' => 'available',
                    ]);
                    $captain = Captain::findOrFail($order->captain_id);
                    app()->setLocale($captain->lang ?? 'ar');

                    // Notify the captain
                    $deviceTokens = $captain->devicetokens()->pluck('token');

                    $title = 'الغاء طلب';
                    $body = 'قام العميل بالغاء طلبه';
                    $tokens = $deviceTokens;
                    $data = [
                        'order_id' => $order->id,
                    ];

                    $this->notifyByFirebase($title, $body, $tokens, $data);
                } else {
                    return response()->json(['status_code' => 404, 'message' => 'Captain not found'], 404);
                }
            }
            $order->update([
                'return_order' => true,
                'order_status_id' => $statusId->id,
                'booking_date' => null,
                'booking_time' => null,
            ]);

            return response()->json(['status_code' => 200, 'message' => 'تم الغاء الطلب بنجاح']);

        }
        return response()->json(['status_code' => 200, 'message' => 'تم الغاء الطلب بنجاح']);

    }

}
