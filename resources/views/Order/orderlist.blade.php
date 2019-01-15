@extends('layout.main')
@section('content')
<table border="1" class="table table-bordered">
    <tr>
        <td>订单名称</td>
        <td>订单金额</td>
        <td>下单时间</td>
        <td>支付状态</td>
    </tr>
    @foreach($data as $v)
        <tr>
            <td>{{$v['order_name']}}</td>
            <td>{{$v['order_price']}}</td>
            <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
            <!-- <td>@if($v['is_pay']==0)
                    <a href="/orderzhi/{{$v['o_id']}}" class="btn btn-info">支付</a>
                @elseif($v['is_pay']==1)
                    <a>已支付</a>
                @endif
            </td> -->
            <td>@if($v['is_pay']==0)
                    <a href="/pay/alipay/test/{{$v['o_id']}}" class="btn btn-info">支付</a>
                @elseif($v['is_pay']==1)
                    <a>已支付</a>
                @endif
            </td>
        </tr>
    @endforeach
</table>
@endsection