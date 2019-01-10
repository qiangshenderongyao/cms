<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>展示</title>
</head>
<body>
<table class="table table-bordered">
    <tr>
        <td>id</td>
        <td>姓名</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
    <tr>
        <td>{{$v->id}}</td>
        <td>{{$v->cname}}</td>
        <td><a href="/delete?id={{$v->id}}">删除</a>|<a href="/update?id={{$v->id}}">修改</a></td>
    </tr>
    @endforeach
</table>
</body>
</html>