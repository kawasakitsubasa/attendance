@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/staff.css') }}">
@endsection

@section('content')
    <div class="page-title">
        <h2>スタッフ一覧</h2>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>月次勤怠</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><a href="/admin/attendance/staff/{{ $user->id }}"><strong>詳細</strong></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection