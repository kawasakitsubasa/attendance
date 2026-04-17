<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者画面</title>
    <link rel="stylesheet" href="{{ asset('css/admin/common.css') }}">
    @yield('css')
</head>
<body>

<header>
    <img src="{{ asset('image/logo.png') }}" alt="COACHTECH">
    <nav>
        <a href="/admin/attendance">勤怠一覧</a>
        <a href="/admin/users">スタッフ一覧</a>
        <a href="/admin/requests">申請一覧</a>
        <form method="POST" action="/admin/logout">
            @csrf
            <button type="submit">ログアウト</button>
        </form>
    </nav>
</header>

<main>
    @yield('content')
</main>

</body>
</html>