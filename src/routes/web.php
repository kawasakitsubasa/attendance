<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// 管理者ログイン
Route::get('/admin/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm']);
Route::post('/admin/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);