<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class OrderExport implements FromView
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = !empty($startDate) ? Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay() : null;
        $this->endDate = !empty($endDate) ? Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay() : null;
    }


    public function view(): View
    {
        if (Auth::user()->role_id == 3) {
            $query = Order::where('user_id', Auth::id());
        } else {
            $query = Order::query();
        }

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->whereBetween('created_at', [
                $this->startDate->startOfDay(),
                $this->endDate->endOfDay(),
            ]);
        }

        $orders = $query->get();

        // Check if no orders are found
        if ($orders->isEmpty()) {
            abort(404, 'No data found');
        }

        return view('exports.orders', ['orders' => $orders]);
    }
}
