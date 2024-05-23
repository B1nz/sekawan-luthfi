<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index() {
        $currentId = Auth::user()->id;

        $activities = ActivityLog::orderByDesc('created_at')->get();

        $activities->transform(function ($activity) {
            $type = $activity->type;
            $target = $this->extractTarget($type);
            $activity->target = $target;

            // dd($activity->target);

            return $activity;
        });

        return view('admin.activity-log', compact('activities'));
    }

    private function extractTarget($type) {
        $parts = explode(' ', $type);
        return isset($parts[1]) ? $parts[1] : '';
    }
}
