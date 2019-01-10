@extends('layout.main')
@section('content')
<table border="1" class="table table-bordered">
    <tr>
        <td>订单名称</td>
        <td>订单金额</td>
        <td>下单时间</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
        <tr>
            <td>{{$v['order_name']}}</td>
            <td>{{$v['order_price']}}</td>
            <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
            <td><!-- <a href="/del/{{$v['order_id']}}">删除</a>&nbsp;&nbsp;&nbsp; --><a href="/orderzhi/{{$v['o_id']}}" class="btn btn-info">支付</a></td>
        </tr>
    @endforeach
</table>
@endsection