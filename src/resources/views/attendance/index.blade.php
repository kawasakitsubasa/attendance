@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app/attendance.css') }}">
@endsection

@section('content')
    <div class="stamp-wrap">

        {{-- ステータスバッジ --}}
        <div class="status-badge">{{ $status }}</div>

        {{-- 日付 --}}
        <div class="date">{{ $now->format('Y年n月j日') }}({{ ['日','月','火','水','木','金','土'][$now->dayOfWeek] }})</div>

        {{-- リアルタイム時計 --}}
        <div class="clock" id="clock">{{ $now->format('H:i') }}</div>

        {{-- ボタン --}}
        @if($status === '勤務外')
            <form method="POST" action="/attendance">
                @csrf
                <input type="hidden" name="action" value="clock_in">
                <button type="submit" class="btn btn-black">出 勤</button>
            </form>

        @elseif($status === '出勤中')
            <div class="btn-group">
                <form method="POST" action="/attendance">
                    @csrf
                    <input type="hidden" name="action" value="clock_out">
                    <button type="submit" class="btn btn-black">退 勤</button>
                </form>
                <form method="POST" action="/attendance">
                    @csrf
                    <input type="hidden" name="action" value="break_in">
                    <button type="submit" class="btn btn-white">休憩入</button>
                </form>
            </div>

        @elseif($status === '休憩中')
           <form method="POST" action="/attendance">
               @csrf
               <input type="hidden" name="action" value="break_out">
               <button type="submit" class="btn btn-white">休憩戻</button>
           </form>

        @elseif($status === '退勤済')
            <p class="done-msg">お疲れ様でした。</p>
        @endif

    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('clock').textContent = h + ':' + m;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
@endsection