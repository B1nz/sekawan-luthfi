<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Quarry;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Models\VehicleHistory;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orders = Order::withTrashed()->get();

        $quarries = Quarry::all();

        $order_request = Order::withTrashed()
            ->where('approver_id', Auth::id())
            ->whereIn('status', ['Requested', 'On Process'])
            ->get();

        $vehicles = Vehicle::all();

        $vehicle_histories = VehicleHistory::selectRaw('
            YEAR(start) as year,
            MONTH(start) as month,
            COUNT(*) as entry_count,
            SUM(fuel_usage) as total_fuel_usage,
            SUM(`range`) as total_range
        ')
        ->where('fuel_usage', '!=', null)
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        // Format the data for the view
        $monthlyUsage = $vehicle_histories->map(function ($item) {
            return [
                'month' => $item->month,
                'year' => $item->year,
                'entry_count' => $item->entry_count,
                'total_fuel_usage' => $item->total_fuel_usage,
                'total_range' => $item->total_range,
            ];
        });

        $user_role = Auth::user()->role_id;

        $data = compact(
            'orders',
            'order_request',
            'quarries',
            'vehicle_histories',
            'vehicles',
            'monthlyUsage',
            'user_role',
        );

        return view('home', $data);
    }
}
