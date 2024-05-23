<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = Office::all();

        return view('admin.office', compact('offices'));
    }

    public function add(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $office = Office::create([
            'name' => $request->name,
            'location' => $request->name,
            'is_hq' => '0',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Add Office',
            'related_id' => $office->id,
        ]);

        return response()->json(['message' => 'Office added successfully!', 'office' => $office]);
    }

    public function edit(Request $request, $id)
    {
        $office = Office::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $office->name = $request->input('name');
        $office->location = $request->input('location');
        $office->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Edit Office',
            'related_id' => $office->id,
        ]);

        return response()->json(['message' => 'Office updated successfully', 'office' => $office]);
    }

    public function delete($id)
    {
        $office = Office::findOrFail($id);

        $office->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Delete Office',
            'related_id' => $office->id,
        ]);

        return response()->json(['message' => 'Office deleted successfully']);
    }
}
