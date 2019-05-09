@extends('layout.bts')
@section('content')
    <table>
        <tr>
            <td>账号</td>
            <td><input type="text" name="name" id="one"></td>
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
            var pwd=$('#pwd').val();
            $.ajax({
                url     :   '/ce/cez',
                type    :   'post',
                data    :   {name:name,pwd:pwd},
                dataType:   'json',
                success :   function(d){

                }
            });
        })
    </script>
@endsection