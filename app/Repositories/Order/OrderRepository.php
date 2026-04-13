<?php

namespace App\Repositories\Order;

use App\Helper\Helper;
use App\Models\Order;
use App\Repositories\Order\OrderInterface;
use Illuminate\Support\Facades\Storage;

class OrderRepository implements OrderInterface
{
    use Helper;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getAll()
    {
        $filters = request()->only(['order_number', 'order_status_id']);
        return $this->order->latest()
            ->with('user', 'car', 'orderStatus', 'captain', 'service', 'userPackage.package')
            ->filter($filters)
            ->paginate();
    }

    public function show($id)
    {
        return $this->order->with('user', 'car', 'captain', 'userPackage.package', 'service', 'choices', 'images', 'orderStatus')
            ->findOrFail($id);
    }

    public function update($request, $order)
    {

        $captain = $order->captain;
//        dd($captain);
        if ($request->order_status_id == 4) {
            if ($captain) {
                $captain->update([
                    'status' => 'available',
                ]);
            }
        }
        if ($request->order_status_id == 11) {
            $order->update([
                'booking_date' => null,
                'booking_time' => null,
            ]);
        }

        $order->update([
            'order_status_id' => $request->order_status_id,
            'updated_by_admin' => true,
            'return_order' => $request->order_status_id == 11 ? true : false,
        ]);

        return $order;
    }

    public function delete($id)
    {
        $order = $this->order->with('images')->findOrFail($id);
        foreach ($order->images as $image) {
            // Delete the image file from storage
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }
        $order->images()->delete();
        $order->delete();

        return $order;
    }
}
