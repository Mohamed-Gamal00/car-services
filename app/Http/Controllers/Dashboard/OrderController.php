<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Services\Payment\InvoiceGenerationService;
use App\Jobs\AssignCaptainToOrder;
use App\Jobs\MakeCaptainAvailableJob;
use App\Models\Captain;
use App\Models\Color;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Package;
use App\Models\Service;
use App\Models\UserPackage;
use App\Repositories\Order\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    use Helper;

    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('order.view');

        $orders = $this->orderRepository->getAll();
        //        dd($orders);
        $defaultOrderStatus = OrderStatus::where('default_status', true)->first();
        $OrderStatus = OrderStatus::all();
        $user = Auth::guard('admin')->user();
        $notifications = $user->notifications()->where('type', "App\Notifications\OrderCreatedNotification")->get();

        return view('dashboard.orders.index', compact('orders', 'defaultOrderStatus', 'notifications', 'OrderStatus'));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('order.edit');

        $availableCaptains = Captain::where('status', 'available')->where('is_active', 1)->get();

        $order = $this->orderRepository->show($id);
        $orderPackage = $order->package;

        $orderStatus = OrderStatus::all();
        return view('dashboard.orders.show', compact('order', 'orderStatus', 'availableCaptains'));
    }

    public function assignCaptain(Request $request, string $id)
    {
        Gate::authorize('order.edit');
        $order = Order::where('id', $id)->where('payment_status', 'paid')->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('danger', 'خطأ اثناء تعيين الكابتن');
        }
        if ($order->captain_id) {
            // لو فيه كابتن سابق، رجّعه متاح
            $captain = Captain::find($order->captain_id);
            if ($captain) {
                $captain->update(['status' => 'available']);
            }
        }


        if ($order) {
            if ($request->captain_id) {
                $captain = Captain::where('id', $request->captain_id)->first();
                $captain->status = 'busy';
                $captain->save();
                $deviceTokens = $captain->devicetokens()->pluck('token');
                app()->setLocale($captain->lang ?? 'ar');

                // $title = __('general.new_notification');
                // $body = __('general.There_is_a_new_request_for_you');
                // $tokens = $deviceTokens;
                // $data = [
                //     'order_id' => 'order',
                // ];
                // $this->notifyByFirebase($title, $body, $tokens, $data);


                $userpackage = UserPackage::with('package')->find($order->user_package_id);
                $service = $order->service->first();

                $duration = $service?->duration ?? $userpackage?->package?->duration;

                dispatch(new MakeCaptainAvailableJob($request->captain_id))
                    ->delay(now()->addMinutes($this->convertTimeToMinutes($duration)));
            }
            $order->update([
                'captain_id' => $request->captain_id,
            ]);

            return \redirect()->route('orders.index')->with('success', __('messages.ORDER_STATUS_UPDATED'));
        } else {
            return \redirect()->route('orders.index')->with('danger', 'خطأ اثناء تعيين الكابتن');
        }
    }

    /*test send notification*/


    public function update(Request $request, string $id)
    {
        Gate::authorize('order.edit');
        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id'
        ]);
        //        dd($request->order_status_id);
        $order = Order::findOrFail($id);
        if ($order->payment_status != 'paid' && $request->order_status_id == 4) {
            return \redirect()->route('orders.index')->with('danger', 'لا يمكنك انهاء الطلب لانه غير مدفوع');
        }
        if ($order->captain_id == null && $request->order_status_id == 3) {
            return \redirect()->route('orders.index')->with('danger', 'هذا الطلب بدون كابتن');
        }

        //        dd($request->all());

        $this->orderRepository->update($request, $order);

        return \redirect()->route('orders.index')->with('success', __('messages.ORDER_STATUS_UPDATED'));
    }

    /**
     * Update the specified resource in storage.
     */

    /*update status*/
    function convertTimeToMinutes($time)
    {
        // Split the time by colon to get hours and minutes
        list($hours, $minutes) = explode(':', $time);

        // Convert the time to minutes
        return ($hours * 60) + $minutes;
    }


//    public function sendNotification(Request $request)
//    {
//
//        //        return 'test';
//        $title = 'إشعار جديد';
//        $body = 'تست تست تست تست';
//        $tokens = [
//            'dir-lwo_To434MYekpHwJ2:APA91bGnVY6LzLK12_FqocmFSByyj_SuetrQrtzS2tlA3i3DPUFbWY0Hc1hbIccUGG0M-S1XIx1BxZb0VP1w7zMTWLRYqWrXALCap57RAQXfOoYPxjXT_rr9d952Zu31x9-skmQNrcGN',
//        ];
//        $data = [
//            'order_id' => 'testtt',
////            "sound" => "default",  // Notification sound for iOS
////            "color" => "#203E78"
//        ];
//        $this->notifyByFirebase($title, $body, $tokens, $data);
//        return redirect()->back();
//    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('order.delete');
        $order = Order::findOrFail($id);

        DB::table('notifications')
            ->where('type', 'App\Notifications\OrderCreatedNotification')
            ->whereJsonContains('data->order_id', $order->id)
            ->delete();


        if ($order->captain_id) {
            // Update the captain's status to available
            $captain = Captain::find($order->captain_id);
            if ($captain) {
                $captain->update(['status' => 'available']);
            }
        }
        $order->update(['is_delete' => 1]);
        return \redirect()->back()->with('dark', __('messages.ORDER_DELETED'));
    }

    /**
     * Regenerate invoice for an order
     */
    public function regenerateInvoice(string $id, InvoiceGenerationService $invoiceService)
    {
        Gate::authorize('order.edit');

        $order = Order::with(['user', 'car', 'service', 'choices', 'userPackage.package'])
            ->findOrFail($id);

        if ($order->payment_status !== 'paid') {
            return redirect()->back()->with('danger', 'لا يمكن إنشاء فاتورة لطلب غير مدفوع');
        }

        try {
            // Generate invoice directly
            $invoicePath = $invoiceService->generateInvoice($order);

            if ($invoicePath) {
                return redirect()->back()->with('success', 'تم إنشاء الفاتورة بنجاح');
            } else {
                return redirect()->back()->with('danger', 'فشل إنشاء الفاتورة. يرجى المحاولة مرة أخرى');
            }
        } catch (\Exception $e) {
            Log::error('Failed to generate invoice from admin', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('danger', 'حدث خطأ أثناء إنشاء الفاتورة: ' . $e->getMessage());
        }
    }

    /**
     * Save Firebase token for push notifications
     */
    public function saveToken(Request $request)
    {
        // This is a placeholder for Firebase token saving
        // You can implement actual token storage here if needed
        return response()->json([
            'success' => true,
            'message' => 'Token saved successfully'
        ]);
    }
}
