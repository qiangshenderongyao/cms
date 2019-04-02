@extends('layout.main')
@section('content')
    <form action="ks/login" method="post">
        @csrf
        <table>
            <h2>用户申请</h2>
            <tr>
                <td>用户名</td>
                <td><input type="text" name="sname" class="form-control" placeholder="nickname" required autofocus></td>
            </tr>
            <td>身份证号</td>
            <td><input type="password" name="password" class="form-control" placeholder="***" required></td>
            <td>上传身份证照片</td>
            <td><input type="file" ></td>
            </tr>
            <tr>
                <td><button class="btn btn-lg btn-primary btn-block" type="submit">登录</button></td>
                <td></td>
            </tr>
        </table>
    </form>
@endsection