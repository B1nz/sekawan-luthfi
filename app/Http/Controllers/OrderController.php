<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Driver;
use App\Models\Quarry;
use App\Models\Vehicle;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\VehicleHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::withTrashed()->orderByDesc('id')->get();

        if (Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            $orders = Order::withTrashed()->where('user_id', Auth::id())->get();
        }

        $approvers = User::where('role_id', 2)->where('id', '!=', Auth::id())->get();

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

        return view('order', $data);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'approver_id' => 'required|integer',
            'quarry_id' => 'required|integer',
            'vehicle_id' => 'required|integer',
            'driver_id' => 'required|integer',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $order = Order::create([
            'user_id' => Auth::id(), // Assuming you want to use the authenticated user's ID
            'approver_id' => $request->approver_id,
            'quarry_id' => $request->quarry_id,
            'vehicle_id' => $request->vehicle_id,
            'driver_id' => $request->driver_id,
            'status' => "Requested",
            'start' => $request->start,
            'end' => $request->end,
        ]);

        if (!$order) {
            return response()->json(['error' => 'Failed to add order'], 500);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Add Order',
            'related_id' => $order->id,
        ]);

        return response()->json(['message' => 'Order added successfully!', 'order' => $order]);
    }


    public function delete($id)
    {
        $order = Order::findOrFail($id);

        // Update the status to "deleted"
        $order->status = "Cancelled";
        $order->save();

        // Soft delete the order
        $order->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Cancel Order',
            'related_id' => $order->id,
        ]);

        return response()->json(['message' => 'Order deleted successfully']);
    }

    public function done(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fuel_usage' => 'required|integer',
            'distance' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        Log::info('vehicle id'.$id);

        $vehicle_history = VehicleHistory::where('order_id', $id)->first();

        Log::info('vehicle hs'.$vehicle_history);

        if (!$vehicle_history) {
            return response()->json(['error' => 'Vehicle history not found for the given order ID'], 404);
        }

        $vehicle_history->fuel_usage = $request->fuel_usage;
        $vehicle_history->range = $request->distance;
        $vehicle_history->save();

        $order = Order::findOrFail($id);

        $order->status = "Done";
        $order->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Finish Order',
            'related_id' => $vehicle_history->id,
        ]);

        return response()->json(['message' => 'Vehicle history updated successfully']);
    }
}
