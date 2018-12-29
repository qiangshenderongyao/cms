<html>
<form action="/add" method="post">
    @csrf
    <table>
        <tr>
            <td>姓名</td>
            <td><input type="text" name="cname"></td>
        </tr>
        <tr>
            <td><input type="submit" value="提交"></td>
            <td></td>
        </tr>
    </table>
</form>
</html>