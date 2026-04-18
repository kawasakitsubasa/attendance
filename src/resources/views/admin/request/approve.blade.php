@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/request_approve.css') }}">
@endsection

@section('content')
    <div class="page-title">
        <h2>勤怠詳細</h2>
    </div>

    <div class="detail-wrap">

        {{-- 名前 --}}
        <div class="row">
            <div class="label">名前</div>
            <div class="value">{{ $correctionRequest->user->name }}</div>
        </div>

        {{-- 日付 --}}
        <div class="row">
            <div class="label">日付</div>
            <div class="value date-value">
                <span>{{ $correctionRequest->target_date->format('Y年') }}</span>
                <span>{{ $correctionRequest->target_date->format('n月j日') }}</span>
            </div>
        </div>

        {{-- 出勤・退勤 --}}
        <div class="row">
            <div class="label">出勤・退勤</div>
            <div class="value time-fields">
                <span>{{ $correctionRequest->clock_in ?? '' }}</span>
                <span class="tilde">〜</span>
                <span>{{ $correctionRequest->clock_out ?? '' }}</span>
            </div>
        </div>

        {{-- 休憩（既存分） --}}
        @foreach($correctionRequest->breakRequests as $i => $break)
        <div class="row">
            <div class="label">休憩{{ $i > 0 ? $i + 1 : '' }}</div>
            <div class="value time-fields">
                <span>{{ $break->start_time ? $break->start_time->format('H:i') : '' }}</span>
                <span class="tilde">〜</span>
                <span>{{ $break->end_time ? $break->end_time->format('H:i') : '' }}</span>
            </div>
        </div>
        @endforeach

        {{-- 休憩2（空欄） --}}
        @if($correctionRequest->breakRequests->count() === 0)
        <div class="row">
            <div class="label">休憩</div>
            <div class="value"></div>
        </div>
        @endif
        <div class="row">
            <div class="label">休憩{{ $correctionRequest->breakRequests->count() + 1 }}</div>
            <div class="value"></div>
        </div>

        {{-- 備考 --}}
        <div class="row">
            <div class="label">備考</div>
            <div class="value">{{ $correctionRequest->reason }}</div>
        </div>

        {{-- 承認ボタン --}}
        @if(!$correctionRequest->is_approved)
        <div class="btn-wrap">
            <form method="POST" action="/stamp_correction_request/approve/{{ $correctionRequest->id }}">
                @csrf
                <button type="submit" class="btn">承認</button>
            </form>
        </div>
        @else
        <div class="btn-wrap">
            <span class="approved-label">承認済み</span>
        </div>
        @endif

    </div>
@endsection