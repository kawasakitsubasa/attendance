@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/request.css') }}">
@endsection

@section('content')
    <div class="page-title">
        <h2>申請一覧</h2>
    </div>

    <div class="tabs">
        <a href="/admin/stamp_correction_request/list?tab=pending"
           class="tab {{ $tab === 'pending' ? 'active' : '' }}">承認待ち</a>
        <a href="/admin/stamp_correction_request/list?tab=approved"
           class="tab {{ $tab === 'approved' ? 'active' : '' }}">承認済み</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>状態</th>
                    <th>名前</th>
                    <th>対象日時</th>
                    <th>申請理由</th>
                    <th>申請日時</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr>
                    <td>{{ $req->is_approved ? '承認済み' : '承認待ち' }}</td>
                    <td>{{ $req->user->name }}</td>
                    <td>{{ $req->target_date->format('Y/m/d') }}</td>
                    <td>{{ $req->reason }}</td>
                    <td>{{ $req->created_at->format('Y/m/d') }}</td>
                    <td><a href="/stamp_correction_request/approve/{{ $req->id }}"><strong>詳細</strong></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection