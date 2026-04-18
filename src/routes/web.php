<?php

use Illuminate\Support\Facades\Route;

// ===== 一般ユーザー =====

// 認証
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm']);
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);

// 勤怠
Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index']);
Route::post('/attendance', [App\Http\Controllers\AttendanceController::class, 'store']);

// 勤怠一覧
Route::get('/attendance/list', [App\Http\Controllers\AttendanceController::class, 'list']);

// 勤怠詳細
Route::get('/attendance/detail/{id}', [App\Http\Controllers\AttendanceController::class, 'detail']);
Route::post('/attendance/detail/{id}', [App\Http\Controllers\AttendanceController::class, 'update']);

// 申請一覧
Route::get('/stamp_correction_request/list', [App\Http\Controllers\StampCorrectionRequestController::class, 'list']);


// ===== 管理者 =====

// 認証
Route::get('/admin/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm']);
Route::post('/admin/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
Route::post('/admin/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout']);

// 勤怠一覧
Route::get('/admin/attendance/list', [App\Http\Controllers\Admin\AttendanceController::class, 'index']);

// スタッフ別勤怠一覧（detailより先に書く！順番大事）
Route::get('/admin/attendance/staff/{id}', [App\Http\Controllers\Admin\AttendanceController::class, 'staff']);

// 勤怠詳細
Route::get('/admin/attendance/{id}', [App\Http\Controllers\Admin\AttendanceController::class, 'detail']);
Route::post('/admin/attendance/{id}', [App\Http\Controllers\Admin\AttendanceController::class, 'update']);

// スタッフ一覧
Route::get('/admin/staff/list', [App\Http\Controllers\Admin\StaffController::class, 'index']);

// 申請一覧・承認
Route::get('/stamp_correction_request/list', [App\Http\Controllers\Admin\StampCorrectionRequestController::class, 'list']);
Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [App\Http\Controllers\Admin\StampCorrectionRequestController::class, 'approve']);
Route::post('/stamp_correction_request/approve/{attendance_correct_request_id}', [App\Http\Controllers\Admin\StampCorrectionRequestController::class, 'store']);