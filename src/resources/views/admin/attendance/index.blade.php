@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/attendance.css') }}">
@endsection

@section('content')
    <div class="page-title">
        <h2>{{ $date->format('Y年n月j日') }}の勤怠</h2>
    </div>

    <div class="date-nav">
        <a href="/admin/attendance?date={{ $date->copy()->subDay()->format('Y-m-d') }}">← 前日</a>
        <span>
            &#128197; {{ $date->format('Y/m/d') }}
        </span>
        <a href="/admin/attendance?date={{ $date->copy()->addDay()->format('Y-m-d') }}">翌日 →</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>名前</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '' }}</td>
                    <td>{{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '' }}</td>
                    <td>{{ $attendance->total_break ?? '' }}</td>
                    <td>{{ $attendance->total_work ?? '' }}</td>
                    <td><a href="/admin/attendance/{{ $attendance->id }}"><strong>詳細</strong></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection