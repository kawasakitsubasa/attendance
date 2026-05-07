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

    public function update(Request $request, $id)
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

        return redirect('/admin/attendance/' . $id);
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

        $attendances = $user->attendances()
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->get();

        return view('admin.staff.attendance', compact('user', 'month', 'days', 'attendances'));
    }

    public function csv(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $month = $request->month
            ? Carbon::parse($request->month . '-01')
            : Carbon::today()->startOfMonth();

        $attendances = $user->attendances()
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->with('breakTimes')
            ->get();

        $filename = $user->name . '_' . $month->format('Y-m') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($attendances) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['日付', '出勤', '退勤', '休憩', '合計']);

            foreach ($attendances as $attendance) {
                $youbi = ['日','月','火','水','木','金','土'][Carbon::parse($attendance->date)->dayOfWeek];
                $date  = Carbon::parse($attendance->date)->format('m/d') . '(' . $youbi . ')';

                $clockIn  = $attendance->clock_in  ? Carbon::parse($attendance->clock_in)->format('H:i')  : '';
                $clockOut = $attendance->clock_out ? Carbon::parse($attendance->clock_out)->format('H:i') : '';
                $break    = $attendance->total_break ?? '';
                $work     = $attendance->total_work  ?? '';

                fputcsv($file, [$date, $clockIn, $clockOut, $break, $work]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}