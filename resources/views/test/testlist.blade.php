@extends('layout.main')
@section('content')
        <table border="1">
            <tr>
                <td>用户</td>
                <td>状态</td>
            </tr>
            @foreach($data as $k =>$v)
            <tr>
                <td>{{$v['username']}}</td>
                <td>{{$v['status']}}</td>
            </tr>
            @endforeach
        </table>
@endsection
@section('foot')
    @parent
@endsection