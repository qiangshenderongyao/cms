@extends('layout.main')
@section('content')
<form action="/test/str" method="post"  class="form-inline">
    @csrf
    <table border="1">
        <tr>
            <td>用户</td>
            <td><input type="text" id="username"></td>
        </tr>
        <tr>
            <td>密码</td>
            <td><input type="password" id="password"></td>
        </tr>
        <tr>
            <td><input type="submit" id="adds" value="登录"></td>
            <td></td>
        </tr>
    </table>
</form>
@endsection
@section('foot')
    @parent
<script src="{{URL::asset('/js/start/start.js')}}"></script>
@endsection