<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\AttendanceCorrectRequest;
use App\Http\Requests\AttendanceDetailRequest;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // 今日の勤怠を取得
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $now->toDateString())
            ->first();

        // ステータスを判定
        if (!$attendance) {
            $status = '勤務外';
        } elseif ($attendance->clock_out) {
            $status = '退勤済';
        } elseif ($attendance->breakTimes()->whereNull('end_time')->exists()) {
            $status = '休憩中';
        } else {
            $status = '出勤中';
        }

        return view('attendance.index', compact('now', 'attendance', 'status'));
    }

    public function store(Request $request)
    {
    $user = Auth::user();
    $now = Carbon::now();
    $action = $request->action;

    // 今日の勤怠を取得or作成
    $attendance = Attendance::firstOrCreate(
        [
            'user_id' => $user->id,
            'date'    => $now->toDateString(),
        ]
    );

    if ($action === 'clock_in') {
        if (!$attendance->clock_in) {
            $attendance->update(['clock_in' => $now]);
        }

    } elseif ($action === 'break_in') {
        $attendance->breakTimes()->create([
            'start_time' => $now,
        ]);

    } elseif ($action === 'break_out') {
        $attendance->breakTimes()
            ->whereNull('end_time')
            ->latest()
            ->first()
            ?->update(['end_time' => $now]);

    } elseif ($action === 'clock_out') {
        if (!$attendance->clock_out) {
            $attendance->update(['clock_out' => $now]);
        }
    }

    return redirect('/attendance');
    }

    public function list(Request $request)
    {
       $user = Auth::user();

       $month = $request->month
          ? Carbon::parse($request->month . '-01')
          : Carbon::today()->startOfMonth();

       $days = collect();
       $start = $month->copy()->startOfMonth();
       $end   = $month->copy()->endOfMonth();
       for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
          $days->push($d->copy());
       }

       $attendances = Attendance::where('user_id', $user->id)
           ->whereYear('date', $month->year)
           ->whereMonth('date', $month->month)
           ->get();

        return view('attendance.list', compact('month', 'days', 'attendances'));
    }

    public function detail($id)
    {
        $attendance = Attendance::with(['user', 'breakTimes'])->findOrFail($id);

        $isPending = AttendanceCorrectRequest::where('attendance_id', $id)
           ->where('is_approved', false)
           ->exists();

          return view('attendance.detail', compact('attendance', 'isPending'));
    }

    public function update(AttendanceDetailRequest $request, $id)
    {
    $attendance = Attendance::findOrFail($id);

    $attendance->update([
        'clock_in'  => $request->clock_in ? Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $request->clock_in) : null,
        'clock_out' => $request->clock_out ? Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $request->clock_out) : null,
        'note'      => $request->note,
    ]);

    if ($request->breaks) {
        $attendance->breakTimes()->delete();
        foreach ($request->breaks as $break) {
            if (!empty($break['start']) || !empty($break['end'])) {
                $attendance->breakTimes()->create([
                    'start_time' => !empty($break['start']) ? Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $break['start']) : null,
                    'end_time'   => !empty($break['end']) ? Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $break['end']) : null,
                ]);
            }
        }
    }

    AttendanceCorrectRequest::create([
        'attendance_id' => $attendance->id,
        'user_id'       => Auth::id(),
        'target_date'   => $attendance->date,
        'reason'        => $request->note ?? '',
        'is_approved'   => false,
        'clock_in'      => $request->clock_in,
        'clock_out'     => $request->clock_out,
    ]);

    return redirect('/attendance/list');
   }
}
