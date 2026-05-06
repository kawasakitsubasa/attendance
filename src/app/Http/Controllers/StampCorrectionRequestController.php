<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCorrectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StampCorrectionRequestController extends Controller
{
    public function list(Request $request)
    {
        $tab = $request->tab ?? 'pending';
        $user = Auth::user();

        // 自分の申請だけ取得
        $requests = AttendanceCorrectRequest::with(['user', 'attendance'])
            ->where('user_id', $user->id)
            ->where('is_approved', $tab === 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('stamp_correction_request.list', compact('requests', 'tab'));
    }
}
