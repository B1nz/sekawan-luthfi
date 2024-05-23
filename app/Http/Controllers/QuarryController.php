<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Quarry;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuarryController extends Controller
{
    public function index()
    {
        $quarries = Quarry::all();
        $offices = Office::where('is_hq', '0')->get();

        return view('admin.quarry', compact('quarries', 'offices'));
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'office_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $quarry = Quarry::create([
            'office_id' => $request->office_id,
            'name' => $request->name,
            'location' => $request->location,
        ]);

        if (!$quarry) {
            return response()->json(['error' => 'Failed to add quarry'], 500);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Add Quarry',
            'related_id' => $quarry->id,
        ]);

        return response()->json(['message' => 'Quarry added successfully!', 'quarry' => $quarry]);
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'office_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $quarry = Quarry::find($id);

        if (!$quarry) {
            return response()->json(['error' => 'Quarry not found'], 404);
        }

        $quarry->office_id = $request->input('office_id');
        $quarry->name = $request->input('name');
        $quarry->location = $request->input('location');
        $quarry->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Edit Quarry',
            'related_id' => $quarry->id,
        ]);

        return response()->json(['message' => 'Quarry updated successfully', 'quarry' => $quarry]);
    }

    public function delete($id)
    {
        $quarry = Quarry::findOrFail($id);

        $quarry->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Delete Quarry',
            'related_id' => $quarry->id,
        ]);

        return response()->json(['message' => 'Quarry deleted successfully']);
    }
}
