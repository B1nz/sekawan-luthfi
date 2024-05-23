<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.user', compact('users'));
    }

    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        // Update only the role_id
        $user->role_id = $request->input('role_id');
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Edit User',
            'related_id' => $user->id,
        ]);

        return response()->json(['message' => 'User role updated successfully', 'user' => $user]);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'Delete User',
            'related_id' => $user->id,
        ]);

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function getRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }
}
