@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app/attendance_list.css') }}">
@endsection

@section('content')
    <div class="page-title">
        <h2>勤怠一覧</h2>
    </div>

    <div class="date-nav">
        <a href="/attendance/list?month={{ $month->copy()->subMonth()->format('Y-m') }}">← 前月</a>
        <span>&#128197; {{ $month->format('Y/m') }}</span>
        <a href="/attendance/list?month={{ $month->copy()->addMonth()->format('Y-m') }}">翌月 →</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>日付</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach($days as $day)
                @php
                    $attendance = $attendances->first(function($a) use ($day) {
                       return \Carbon\Carbon::parse($a->date)->format('Y-m-d') === $day->format('Y-m-d');
                    });
                    $youbi = ['日','月','火','水','木','金','土'][$day->dayOfWeek];
                @endphp
                <tr>
                    <td>{{ $day->format('m/d') }}({{ $youbi }})</td>
                    <td>{{ $attendance && $attendance->clock_in ? $attendance->clock_in->format('H:i') : '' }}</td>
                    <td>{{ $attendance && $attendance->clock_out ? $attendance->clock_out->format('H:i') : '' }}</td>
                    <td>{{ $attendance ? $attendance->total_break : '' }}</td>
                    <td>{{ $attendance ? $attendance->total_work : '' }}</td>
                    <td>
                        @if($attendance)
                            <a href="/attendance/detail/{{ $attendance->id }}"><strong>詳細</strong></a>
                        @else
                            <strong>詳細</strong>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection