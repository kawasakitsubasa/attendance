<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;

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
        // 後で実装
    }

    public function detail($id)
    {
        // 後で実装
    }

    public function update(Request $request, $id)
    {
        // 後で実装
    }
}
