<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();

        return view('admin.driver', compact('drivers'));
    }

    public function add(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $driver = Driver::create([
            'name' => $request->name,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Add Driver',
            'related_id' => $driver->id,
        ]);

        return response()->json(['message' => 'Driver added successfully!', 'driver' => $driver]);
    }

    public function edit(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $driver->name = $request->input('name');
        $driver->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Edit Driver',
            'related_id' => $driver->id,
        ]);

        return response()->json(['message' => 'Driver updated successfully', 'driver' => $driver]);
    }

    public function delete($id)
    {
        $driver = Driver::findOrFail($id);

        $driver->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Delete Driver',
            'related_id' => $driver->id,
        ]);

        return response()->json(['message' => 'Driver deleted successfully']);
    }
}
