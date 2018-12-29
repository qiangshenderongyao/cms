<html>
<form action="/update_add" method="post">
    <input type="text" name="id" value="{{$res->id}}">
    @csrf
    <table>
        <tr>
            <td>姓名</td>
            <td><input type="text" name="cname" value="{{$res->cname}}"></td>
        </tr>
        <tr>
            <td><input type="submit" value="提交"></td>
            <td></td>
        </tr>
    </table>
</form>
</html>