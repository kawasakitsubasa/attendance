<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCorrectRequest;
use Illuminate\Http\Request;

class StampCorrectionRequestController extends Controller
{
    public function list(Request $request)
    {
        $tab = $request->tab ?? 'pending';

        $requests = AttendanceCorrectRequest::with(['user', 'attendance'])
            ->where('is_approved', $tab === 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.request.index', compact('requests', 'tab'));
    }

    public function approve($id)
    {
         $correctionRequest = AttendanceCorrectRequest::with(['user', 'attendance'])
             ->findOrFail($id);

         return view('admin.request.approve', compact('correctionRequest'));
    }

    public function store(Request $request, $id)
    {
         $correctionRequest = AttendanceCorrectRequest::findOrFail($id);

         $correctionRequest->update(['is_approved' => true]);

         $attendance = $correctionRequest->attendance;
         $attendance->update([
            'clock_in'  => $correctionRequest->clock_in,
            'clock_out' => $correctionRequest->clock_out,
            'note'      => $correctionRequest->reason,
        ]);

        return redirect('/stamp_correction_request/list?tab=approved');
    }
}
