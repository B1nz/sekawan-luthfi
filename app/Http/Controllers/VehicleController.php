<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\VehicleHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();

        return view('admin.vehicle', compact('vehicles'));
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registration_number' => 'required|string|max:255',
            'type' => 'required|string|in:barang,penumpang',
            'owner' => 'required|string|in:perusahaan,sewa',
            'renter' => 'nullable|string|max:255',
            'service' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $vehicle = Vehicle::create([
            'registration_number' => $request->registration_number,
            'type' => $request->type,
            'owner' => $request->owner,
            'renter' => $request->renter,
            'service' => $request->service,
        ]);

        if (!$vehicle) {
            return response()->json(['error' => 'Failed to add vehicle'], 500);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Add Vehicle',
            'related_id' => $vehicle->id,
        ]);

        return response()->json(['message' => 'Vehicle added successfully!', 'vehicle' => $vehicle]);
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'registration_number' => 'required|string|max:255',
            'type' => 'required|string|in:barang,penumpang',
            'owner' => 'required|string|in:perusahaan,sewa',
            'renter' => 'nullable|string|max:255',
            'service' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        $vehicle->registration_number = $request->input('registration_number');
        $vehicle->type = $request->input('type');
        $vehicle->owner = $request->input('owner');
        $vehicle->renter = $request->input('renter');
        $vehicle->service = $request->input('service');
        $vehicle->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Edit Vehicle',
            'related_id' => $vehicle->id,
        ]);

        return response()->json(['message' => 'Vehicle updated successfully!', 'vehicle' => $vehicle]);
    }

    public function delete($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $vehicle->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Delete Vehicle',
            'related_id' => $vehicle->id,
        ]);

        return response()->json(['message' => 'Vehicle deleted successfully!']);
    }

    public function getHistory($id)
    {
        $vehicleHistory = VehicleHistory::where('vehicle_id', $id)->get();
        return response()->json($vehicleHistory);
    }
}
