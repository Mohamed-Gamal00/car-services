<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Captain\CaptainRequest;
use App\Http\Requests\Dashboard\Captain\CaptainUpdatPasswordRequest;

use App\Models\Captain;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Repositories\Captains\CaptainRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CaptainController extends Controller
{
    public $captainRepo;

    public function __construct(CaptainRepository $repo)
    {
        $this->captainRepo = $repo;
    }

    public function index()
    {
        Gate::authorize('captain.view');
        $captains = $this->captainRepo->getMainCaptain();

        return view('dashboard.captains.index', compact('captains'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'unique:captains',
                'regex:/^05\d{8}$/',
                'unique:users,phone_number',
            ],
            'password' => 'required|min:6|confirmed',
        ]);


        $user = Captain::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password)
        ]);
        $token = $user->createToken('API Token')->plainTextToken;
        return redirect()->route('captains.index')->with('success', 'تم اضافة كابتن جديد.');
    }

    public function create()
    {
        Gate::authorize('captain.create');
        return view('dashboard.captains.create');
    }

    public function show(string $id)
    {
        Gate::authorize('captain.show');
        $captain = Captain::findOrFail($id);
        $completed_orders = Order::latest()
            ->with('products', 'orderStatus', 'choices')
            ->where('captain_id', $captain->id)
            ->where('order_status_id', 4)
            ->where('return_order', false)
            ->paginate(10);

        $current_order = $captain->orders()->where('order_status_id', '!=', 4)->where('return_order', false)->first();

        $wating_list = $captain->orders()->where('order_status_id', '!=', 4)->where('return_order', false)->paginate(10);

//        dd($current_order);
        return view('dashboard.captains.show', compact('captain', 'completed_orders', 'current_order', 'wating_list'));
    }

    public function edit(string $id)
    {
        Gate::authorize('captain.edit');
        $captain = Captain::findOrFail($id);
        return view('dashboard.captains.edit', compact('captain'));
    }

    public function updatePassword(CaptainUpdatPasswordRequest $request, string $id)
    {
        $request->validated();
        $captain = Captain::findOrFail($id);
        $captain->update([
            'password' => Hash::make($request->password)
        ]);
        return back()->with('success', __('messages.CLIENT_UPDATED_PASSWORD'));
    }

    public function update(CaptainRequest $request, string $id)
    {
        $data = $request->validated();

        // تحقق من إمكانية تحديث حالة الكابتن
        $canBeUpdated = $this->captainRepo->canUpdateCaptainStatus($id);

        if (!$canBeUpdated) {
            $ordersLink = route('captains.show', $id);
            $message = 'هذا الكابتن لديه طلبات في قائمة الانتظار لمشاهدة قائمة الانتظار. <a href="' . $ordersLink . '"> اضغط هنا</a>';

            return redirect()->back()->with('danger', $message);
        }

        $wasChanged = $this->captainRepo->update($data, $id);

        if ($wasChanged) {
            return redirect()->back()->with('success', __('messages.CLIENT_UPDATED'));
        }
        return redirect()->back()->with('success', __('messages.CLIENT_UPDATED'));
    }

    public function destroy(string $id)
    {
        $this->captainRepo->delete($id);
        return back()->with('dark', __('messages.CLIENT_DELETED'));
    }

    public function rating($id)
    {
        Gate::authorize('captain.show');
        $captain = Captain::findOrFail($id);
        $completed_orders = Order::latest()
            ->with('products', 'orderStatus', 'choices', 'rating')
            ->where('captain_id', $captain->id)
            ->where('order_status_id', 4)
            ->where('return_order', false)
            ->paginate(5);
//        dd($captain->ratings);
        return view('dashboard.captains.rating', compact('captain', 'completed_orders'));
    }

}
