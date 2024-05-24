<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Driver;
use App\Models\Quarry;
use App\Models\Vehicle;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\OrderApproval;
use App\Models\VehicleHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderRequestController extends Controller
{
    public function index()
    {
        $orders = Order::withTrashed()
            ->where('approver_id', Auth::id())
            ->whereIn('status', ['Requested', 'On Process'])
            ->get();

        $approvers = User::where('role_id', 1)->where('id', '!=', Auth::id())->get();

        $quarries = Quarry::all();

        $drivers = Driver::all();

        $vehicles = Vehicle::all();

        $data = compact(
            'orders',
            'approvers',
            'quarries',
            'drivers',
            'vehicles'
        );

        return view('admin.order-request', $data);
    }

    public function approve(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'approver_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($request->approver_id == 0) {
            $order->status = 'Approved';
        } else {
            $order->status = 'On Process';
            $order->approver_id = $request->approver_id;
        }

        $order->save();

        Log::info('Order id: ' . $id);
        Log::info('Order id2: ' . $order->id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Approve Order',
            'related_id' => $order->id,
        ]);

        OrderApproval::create([
            'order_id' => $order->id,
            'approver_id' => Auth::id(),
            'is_approved' => true,
        ]);

        if ($request->approver_id == 0) {
            VehicleHistory::create([
                'order_id' => $order->id,
                'vehicle_id' => $order->vehicle_id,
                'user_id' => $order->user_id,
                'driver_id' => $order->driver_id,
                'start' => $order->start,
                'end' => $order->end,
            ]);
        }

        return response()->json(['message' => 'Order Approved', 'order' => $order]);
    }

    public function reject($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->status = 'Rejected';
        $order->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Reject Order',
            'related_id' => $order->id,
        ]);

        OrderApproval::create([
            'order_id' => $order->id,
            'approver_id' => Auth::id(),
            'is_approved' => false,
        ]);

        return response()->json(['message' => 'Order Rejected']);
    }
}
