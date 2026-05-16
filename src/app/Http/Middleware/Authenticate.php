<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            // 管理者ルートは管理者ログイン画面へ
            if ($request->is('admin/*')) {
                return route('admin.login');
            }
            return route('login');
        }
    }
}
