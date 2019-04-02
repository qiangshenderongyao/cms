@extends('layout.main')
@section('content')
    <form action="/ks/login/add" method="post" action="/profile">
        @csrf
        <table>
            <h2>用户申请</h2>
            <tr>
                <td>用户名</td>
                <td><input type="text" name="sname" class="form-control" placeholder="nickname" required autofocus></td>
            </tr>
            <tr>
            <td>身份证号</td>
            <td><input type="password" name="shenfen" class="form-control" placeholder="***" required></td>
            </tr>
            <tr>
            <td>上传身份证照片</td>
            <td><input type="file" name="file" class="form-control"></td>
            </tr>
            <tr>
                <td>接口用途</td>
                <td><input type="text" class="form-control" name="yt"></td>
            </tr>
            <tr>
                <td><button class="btn btn-lg btn-primary btn-block" type="submit">登录</button></td>
                <td></td>
            </tr>
        </table>
    </form>
@endsection
@section('foot')
    @parent
    <script src="{{URL::asset('/js/kslogin/kslogin.js')}}"></script>
@endsection