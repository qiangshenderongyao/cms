@extends('layout.main')
@section('content')
        <tr>
            <td>购物车详情</td>
            <td>操作</td>
        </tr>
        <hr>
        @foreach($list as $v)
            <tr>
                <td>{{$v['goods_name']}}  -  {{$v['price']}}   --  {{$v['add_time']}}  ---{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                <td><a href="/del/{{$v['goods_id']}}">删除</a></td>
            </tr><hr>
        @endforeach
        <a href="/order" id="submit_order" class="btn btn-info "> 提交订单 </a>
@endsection