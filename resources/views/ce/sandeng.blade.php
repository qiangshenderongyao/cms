@extends('layout.bts')
@section('content')
    <table>
        <tr>
            <td>账号</td>
            <td><input type="text" name="name" id="one"></td>
        </tr>
        <tr>
            <td>邮箱</td>
            <td><input type="email" name="email" id="two"></td>
        </tr>
        <tr>
            <td>手机号</td>
            <td><input type="text" name="iPhone" id="three"></td>
        </tr>
        <tr>
            <td>密码</td>
            <td><input type="password" name="password" id="pwd"></td>
        </tr>
        <tr>
            <td><button class="layui-btn" id="dl">注册</button></td>
            <td></td>
        </tr>
    </table>
    <script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>
    <script src="{{URL::asset('/bootstrap/js/bootstrap.min.js')}}"></script>
    <script>
        $('#dl').click(function(){
            var name=$('#one').val();
            var email=$('#two').val();
            var iPhone=$('#three').val();
            var pwd=$('#pwd').val();
            $.ajax({
                url     :   '/ce/sandengadd',
                type    :   'post',
                data    :   {name:name,email:email,iPhone:iPhone,pwd:pwd},
                dataType:   'json',
                success :   function(d){
                    if(d.status==1000){
                        alert('提交成功');
                        window.location.href='/ce/sandenglist';
                    }else{
                        alert('失败');
                    }
                }
            });
        })
    </script>
@endsection