<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

function logActivity($activity, $module = null, $details = null)
{
    ActivityLog::create([
        'user_id' => Auth::check() ? Auth::id() : null,
        'activity' => $activity,
        'module' => $module,
        'details' => is_array($details) ? json_encode($details) : $details,
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);
}
