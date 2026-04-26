@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app/login.css') }}">
@endsection

@section('content')
    <h2>ログイン</h2>

    <form method="POST" action="/login">
        @csrf

        {{-- エラーメッセージ（FN009） --}}
        @if($errors->any())
            <div class="error-box">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password">
        </div>

        <button type="submit" class="btn">ログインする</button>

        <div class="register-link">
            <a href="/register">会員登録はこちら</a>
        </div>
    </form>
@endsection