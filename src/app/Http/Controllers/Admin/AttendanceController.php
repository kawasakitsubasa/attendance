<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        
        $date = $request->date
            ? Carbon::parse($request->date)
            : Carbon::today();

        // その日の全ユーザーの勤怠を取得
        $attendances = Attendance::with('user')
            ->whereDate('date', $date)
            ->get();

        return view('admin.attendance.index', compact('date', 'attendances'));
    }
}