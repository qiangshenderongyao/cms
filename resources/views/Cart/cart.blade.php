@extends('layout.main')
@section('content')
    <table border="1">
        <tr>
            <td>id</td>
            <td>姓名</td>
            <td>操作</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td>{{$v->goods_id}}</td>
                <td>{{$v->goods_name}}</td>
                <td><a href="/delete?id={{$v->id}}">删除</a>|<a href="/update?id={{$v->id}}">修改</a></td>
            </tr>
        @endforeach
    </table>
@endsection