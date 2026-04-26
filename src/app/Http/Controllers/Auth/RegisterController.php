<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
      if (Auth::check()) {
        return redirect('/attendance');
       }
       return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
      $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
      ]);

      Auth::login($user);

    // メール認証あり → verify画面へ
    return redirect('/email/verify');
    }
}