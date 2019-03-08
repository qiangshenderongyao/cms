@extends('layout.bts')
@section('content')
<form class="form-inline">
    @csrf
    <table>
        <tr>
            <td>复选框</td>
            <td>nickname</td>
            <td>sex</td>
            <td>headimgurl</td>
            <td>标签</td>
        </tr>
        @foreach($data as $k=>$v)
        <tr>
            <td><input type="checkbox" name="openid" id="openid" value="{{$v['openid']}}" /></td>
            <td>{{$v['nickname']}}</td>
            <td>{{$v['sex']}}</td>
            <td><img src="{{$v['headimgurl']}}"></td>
            <td>@if($v['biaoqian']=='')
                <button>无</button>
                @elseif(!$v['biaoqian']=='')
                {{$v['biaoqian']}}
                @endif
            </td>
        </tr>
        @endforeach
        {{$data->links()}}
        <input type="button" id="add_cart_btn" value="朋友">
        <input type="button" id="fensi" value="粉丝">
    </table>
</form>
@endsection
<script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>
<script>
    //给按钮一个点击事件
    $('#add_cart_btn').click(function (data) {
        //preventDefault() 方法阻止元素发生默认的行为（例如，当点击提交按钮时阻止对表单的提交）。
        data.preventDefault();
        //获取值
        var openid=$('#openid').val();
        var biao=$("#add_cart_btn").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url     :   '/weixin/listadd',
            type    :   'post',
            data    :   {openid:openid,biao:biao},
            dataType:   'json',
            success :   function(d){

            }
        });
    });
</script>