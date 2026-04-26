@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app/register.css') }}">
@endsection

@section('content')
    <h2>会員登録</h2>

    <form method="POST" action="/register">
        @csrf

        {{-- エラーメッセージ（FN003） --}}
        @if($errors->any())
            <div class="error-box">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="form-group">
            <label>名前</label>
            <input type="text" name="name" value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password">
        </div>

        <div class="form-group">
            <label>パスワード確認</label>
            <input type="password" name="password_confirmation">
        </div>

        <button type="submit" class="btn">登録する</button>

        <div class="login-link">
            <a href="/login">ログインはこちら</a>
        </div>
    </form>
@endsection