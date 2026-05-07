<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// ===== 一般ユーザー =====

// 認証
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm']);
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);

// メール認証
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/attendance');
})->middleware(['auth', 'signed'])->name('verification.verify');

// ログイン必須のルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index']);
    Route::post('/attendance', [App\Http\Controllers\AttendanceController::class, 'store']);
    Route::get('/attendance/list', [App\Http\Controllers\AttendanceController::class, 'list']);
    Route::get('/attendance/detail/{id}', [App\Http\Controllers\AttendanceController::class, 'detail']);
    Route::post('/attendance/detail/{id}', [App\Http\Controllers\AttendanceController::class, 'update']);
    Route::get('/stamp_correction_request/list', [App\Http\Controllers\StampCorrectionRequestController::class, 'list']);
});


// ===== 管理者 =====

// 認証
Route::get('/admin/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm']);
Route::post('/admin/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
Route::post('/admin/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout']);

// 勤怠一覧
Route::get('/admin/attendance/list', [App\Http\Controllers\Admin\AttendanceController::class, 'index']);

// スタッフ別勤怠一覧
Route::get('/admin/attendance/staff/{id}', [App\Http\Controllers\Admin\AttendanceController::class, 'staff']);

// CSV出力
Route::get('/admin/attendance/staff/{id}/csv', [App\Http\Controllers\Admin\AttendanceController::class, 'csv']);

// 勤怠詳細
Route::get('/admin/attendance/{id}', [App\Http\Controllers\Admin\AttendanceController::class, 'detail']);
Route::post('/admin/attendance/{id}', [App\Http\Controllers\Admin\AttendanceController::class, 'update']);

// スタッフ一覧
Route::get('/admin/staff/list', [App\Http\Controllers\Admin\StaffController::class, 'index']);

// 申請一覧・承認（管理者）
Route::get('/admin/stamp_correction_request/list', [App\Http\Controllers\Admin\StampCorrectionRequestController::class, 'list']);
Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [App\Http\Controllers\Admin\StampCorrectionRequestController::class, 'approve']);
Route::post('/stamp_correction_request/approve/{attendance_correct_request_id}', [App\Http\Controllers\Admin\StampCorrectionRequestController::class, 'store']);