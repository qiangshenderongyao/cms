@extends('layout.main')
@section('content')
<form class="form-signin" action="/zhuce" method="post">
    @csrf
    <table>
    	<h2>用户注册</h2>
        <tr>
            <td>用户名</td>
            <td><input type="text" name="cname" id="inputNickName" class="form-control" placeholder="nickname" required autofocus></td>
        </tr>
        <tr>
        	<td>年龄</td>
        	<td><input type="text" name="age" id="inputAge" class="form-control" placeholder="nickname" required autofocus></td>
        </tr>
        	<td>密码</td>
        	<td><input type="password" name="password" id="inputNickpassword" class="form-control" placeholder="***" required></td>
        </tr>
        <tr>
        	<td>确认密码</td>
        	<td><input type="password" name="pwd" id="inputNickpwd" class="form-control" placeholder="***" required></td>
        </tr>
        <tr>
            <td><button class="btn btn-lg btn-primary btn-block" type="submit">注册</button></td>
            <td></td>
        </tr>
    </table>
</form>
</body>
</html>
@endsection