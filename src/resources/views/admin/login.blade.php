<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
    <link rel="stylesheet" href="{{ asset('css/admin/login.css') }}">
</head>
<body>

<header>
    <img src="{{ asset('image/logo.png') }}" alt="COACHTECH">
</header>

<div class="container">
    <h2>管理者ログイン</h2>

    <form method="POST" action="/admin/login">
        @csrf

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <label>メールアドレス</label>
        <input type="email" name="email" value="{{ old('email') }}">

        <label>パスワード</label>
        <input type="password" name="password">

        <button type="submit" class="btn">管理者ログインする</button>
    </form>
</div>

</body>
</html>