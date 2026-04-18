@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/attendance_detail.css') }}">
@endsection

@section('content')
    <div class="page-title">
        <h2>勤怠詳細</h2>
    </div>

    <div class="detail-wrap">
        <form method="POST" action="/admin/attendance/{{ $attendance->id }}">
            @csrf

            {{-- エラーメッセージ --}}
            @if($errors->any())
                <div class="error-box">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- 名前 --}}
            <div class="row">
                <div class="label">名前</div>
                <div class="value">{{ $attendance->user->name }}</div>
            </div>

            {{-- 日付 --}}
            <div class="row">
                <div class="label">日付</div>
                <div class="value date-value">
                    <span>{{ $attendance->date->format('Y年') }}</span>
                    <span>{{ $attendance->date->format('n月j日') }}</span>
                </div>
            </div>

            {{-- 出勤・退勤 --}}
            <div class="row">
                <div class="label">出勤・退勤</div>
                <div class="value time-fields">
                    <input type="text" name="clock_in"
                        value="{{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '' }}"
                        placeholder="00:00">
                    <span class="tilde">〜</span>
                    <input type="text" name="clock_out"
                        value="{{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '' }}"
                        placeholder="00:00">
                </div>
            </div>

            {{-- 休憩（既存分） --}}
            @foreach($attendance->breakTimes as $i => $break)
            <div class="row">
                <div class="label">休憩{{ $i > 0 ? $i + 1 : '' }}</div>
                <div class="value time-fields">
                    <input type="text" name="breaks[{{ $i }}][start]"
                        value="{{ $break->start_time ? $break->start_time->format('H:i') : '' }}"
                        placeholder="00:00">
                    <span class="tilde">〜</span>
                    <input type="text" name="breaks[{{ $i }}][end]"
                        value="{{ $break->end_time ? $break->end_time->format('H:i') : '' }}"
                        placeholder="00:00">
                </div>
            </div>
            @endforeach

            {{-- 休憩（追加の空欄） --}}
            <div class="row">
                <div class="label">休憩{{ $attendance->breakTimes->count() > 0 ? $attendance->breakTimes->count() + 1 : '' }}</div>
                <div class="value time-fields">
                    <input type="text" name="breaks[{{ $attendance->breakTimes->count() }}][start]" placeholder="00:00">
                    <span class="tilde">〜</span>
                    <input type="text" name="breaks[{{ $attendance->breakTimes->count() }}][end]" placeholder="00:00">
                </div>
            </div>

            {{-- 備考 --}}
            <div class="row">
                <div class="label">備考</div>
                <div class="value">
                    <textarea name="note" rows="3">{{ $attendance->note }}</textarea>
                </div>
            </div>

            <div class="btn-wrap">
                <button type="submit" class="btn">修正</button>
            </div>
        </form>
    </div>
@endsection