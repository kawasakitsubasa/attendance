<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/app/common.css') }}">
    @yield('css')
</head>
<body>

<header>
    <img src="{{ asset('image/logo.png') }}" alt="COACHTECH">
    @if(Auth::check() && Auth::user()->hasVerifiedEmail())
<nav>
    @if(isset($status) && $status === '退勤済')
        <a href="/attendance/list">今月の出勤一覧</a>
        <a href="/stamp_correction_request/list">申請一覧</a>
    @else
        <a href="/attendance">勤怠</a>
        <a href="/attendance/list">勤怠一覧</a>
        <a href="/stamp_correction_request/list">申請</a>
    @endif
    <form method="POST" action="/logout">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
</nav>
@endif
</header>

<main>
    @yield('content')
</main>

</body>
</html>