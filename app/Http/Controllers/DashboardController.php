<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Models\Order;
use App\Models\Service;
use App\Models\Package;
use App\Models\Captain;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $servicesCount = Service::count();
        $packagesCount = Package::count();
        $ordersCount = Order::count();
        $usersCount = User::count();
        $captainsCount = Captain::count();
        $messagesCount = ContactUs::count();
        $adminsCount = \App\Models\Admin::count();
        
        // Recent statistics
        $todayOrders = Order::whereDate('created_at', today())->count();
        $availableCaptains = Captain::where('status', 'available')->where('is_active', true)->count();
        $busyCaptains = Captain::where('status', 'busy')->where('is_active', true)->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_price');
        
        return view('dashboard.dashboard', compact(
            'servicesCount', 
            'packagesCount', 
            'ordersCount', 
            'usersCount', 
            'captainsCount',
            'adminsCount', 
            'messagesCount',
            'todayOrders',
            'availableCaptains',
            'busyCaptains',
            'totalRevenue'
        ));
    }
}