@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app/verify.css') }}">
@endsection

@section('content')
    <div class="verify-wrap">
        <p class="message">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        {{-- 認証はこちらからボタン（メーラーを開く） --}}
        <a href="http://localhost:8025" class="btn-verify">認証はこちらから</a>

        {{-- 認証メール再送（FN012） --}}
        <form method="POST" action="/email/verification-notification">
            @csrf
            <button type="submit" class="resend-link">認証メールを再送する</button>
        </form>
    </div>
@endsection