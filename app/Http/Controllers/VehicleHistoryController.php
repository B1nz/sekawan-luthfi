<?php

namespace App\Http\Controllers;

use App\Models\VehicleHistory;
use Illuminate\Support\Facades\Log;

class VehicleHistoryController extends Controller
{
    public function getVehicleHistory($id)
    {
        $vehicleHistories = VehicleHistory::with(['user', 'driver'])
        ->where('vehicle_id', $id)
        ->get();

        return response()->json($vehicleHistories);
    }
}
