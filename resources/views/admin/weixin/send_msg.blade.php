<form action="/admin/weixin/sendmsg" method="post">
    @csrf
    <textarea name="msg" id="" cols="100" rows="10"></textarea>
    <input type="submit" value="Send">
</form>