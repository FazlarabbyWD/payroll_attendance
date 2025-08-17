<?php
namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceProcess;
use App\Models\DeviceSyncLog;

class AttendanceLogController extends Controller
{
    public function index()
    {

        $lastAttSync = DeviceSyncLog::with('device')->where('type', 'attendance')->get();
        $lastAttPros = AttendanceProcess::latest('last_processed_at')->first();

        return view('attendance.log', compact('lastAttSync', 'lastAttPros'));
    }
}
