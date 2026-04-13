<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\DiscountCodesExport;
use App\Exports\OrdersExport;
use App\Exports\ShipmentsExport;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Service;
use App\Models\User;
use App\Repositories\Reports\ReportsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    protected $reportRepository;

    public function __construct(ReportsRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

//

    public function index(Request $request)
    {

        $reportTitle = [
            'orders-report' => 'تقرير الطلبات ',
            'coupons-report' => 'تقرير كوبونات الخصم ',
            'customers-report' => 'تقرير عن العملاء الحاليين ',
        ];


        // Check if the request has parameters
        if ($request->has('report_type') && $request->report_type) {
            $OrderStatus = OrderStatus::all();

            $reportType = $request->report_type;
            $startAt = $request->start_at ?? null;
            $endAt = $request->end_at ?? null;

            $result = $this->reportRepository->searchReport($reportType, $startAt, $endAt);
            session(['report_data' => $result]);
            switch ($reportType) {
                case 'orders-report':
                    return view('dashboard.reports.index', [
                        'orders' => $result,
                        'reportType' => $reportType,
                        'reportTitle' => $reportTitle,
                        'OrderStatus' => $OrderStatus,
                    ]);

                case 'customers-report':
                    return view('dashboard.reports.index', [
                        'clients' => $result,
                        'reportType' => $reportType,
                        'reportTitle' => $reportTitle,
                    ]);


                case 'coupons-report':
                    return view('dashboard.reports.index', [
                        'discounts' => $result,
                        'reportType' => $reportType,
                        'reportTitle' => $reportTitle,
                    ]);

                default:
                    return redirect()->back()->with('error', 'Invalid report type.');
            }
        }

        // If no filter is applied
        return view('dashboard.reports.index', compact('reportTitle'));
    }

//    public function export()
//    {
//
//
//        switch (request('report_type')) {
//            case 'products-report':
//
//
//            case 'customers-report':
//                // Fetch paginated data
//                $query = User::query();
//
//                if (request('start_at')) {
//                    $query->whereDate('created_at', '>=', request('start_at'));
//                }
//
//                if (request('end_at')) {
//                    $query->whereDate('created_at', '<=', request('end_at'));
//                }
//                $paginatedData = $query->select(['first_name', 'phone_number', 'created_at'])->paginate(15);
//                // Pass paginated items to UsersExport
//                return Excel::download(new UsersExport($paginatedData->items()), 'clients.xlsx');
//
//            case 'coupons-report':
//                // Fetch paginated data
//                $query = DiscountCode::query();
//
//                if (request('start_at')) {
//                    $query->whereDate('created_at', '>=', request('start_at'));
//                }
//
//                if (request('end_at')) {
//                    $query->whereDate('created_at', '<=', request('end_at'));
//                }
//                $paginatedData = $query->select(['code', 'price', 'discount_type', 'status', 'number_of_used'])->paginate(15);
//                return Excel::download(new DiscountCodesExport($paginatedData->items()), 'coupons.xlsx');
//
//            case 'orders-report':
//                // Initialize query with eager loading relationships
//                $query = Order::with(['orderItems', 'user', 'orderStatus']);
//
//                // Apply filters based on the request
//                $filters = request()->only(['order_status_id', 'start_at', 'end_at']);  // Fetch filters from request
//                $query->filter($filters);  // Apply the filters via the scopeFilter method
//
//                // Fetch filtered data (use `paginate` for paginated data)
//                $filteredData = $query->paginate(15);
//
//                // Pass the filtered data to the export class
//                return Excel::download(new OrdersExport($filteredData->items()), 'orders.xlsx');
//
//
//            default:
//                return redirect()->back()->with('error', 'Invalid report type.');
//        }
//    }

    public function export()
    {
        $reportData = session('report_data'); // Retrieve the filtered data from the session

        if (!$reportData) {
            return redirect()->back()->with('error', 'No data available for export.');
        }

        switch (request('report_type')) {
            case 'orders-report':
                // Pass the session-stored filtered data to the OrdersExport class
                return Excel::download(new OrdersExport($reportData->items()), 'orders.xlsx');
            case 'customers-report':
                return Excel::download(new UsersExport($reportData->items()), 'clients.xlsx');
            case 'coupons-report':
                return Excel::download(new DiscountCodesExport($reportData->items()), 'coupons.xlsx');
            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }
    }


}
