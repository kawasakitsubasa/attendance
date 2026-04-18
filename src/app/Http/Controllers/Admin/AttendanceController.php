<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date
               ? Carbon::parse($request->date)
               : Carbon::today();

        $attendances = Attendance::with('user')
                ->whereDate('date', $date)
                ->get();

        return view('admin.attendance.index', compact('date', 'attendances'));
    }
    public function detail($id)
    {
        $attendance = Attendance::with(['user', 'breakTimes'])->findOrFail($id);
        return view('admin.attendance.detail', compact('attendance'));
    }

    public function staff(Request $request, $id)
    {
         $user = User::findOrFail($id);

         $month = $request->month
              ? Carbon::parse($request->month . '-01')
              : Carbon::today()->startOfMonth();

         $days = collect();
         $start = $month->copy()->startOfMonth();
         $end   = $month->copy()->endOfMonth();
         for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
             $days->push($d->copy());
         }

    // その月のそのユーザーの勤怠を全取得
         $attendances = $user->attendances()
             ->whereYear('date', $month->year)
             ->whereMonth('date', $month->month)
             ->get();

         return view('admin.staff.attendance', compact('user', 'month', 'days', 'attendances'));
    }
}