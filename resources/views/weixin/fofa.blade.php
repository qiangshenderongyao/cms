<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container">
        聊天啦:{{$openid}}
        <div id="chat_div" class="chat">

        </div><hr>
        <form action="" class="form-inline">
            <input type="hidden" value="{{$openid}}" id="openid">
            <input type="hidden" value="1" id="msg_pos">
            <input type="hidden" value="0" id="msg_posd">
            <textarea name="" id="send_msg" cols="100" rows="10"></textarea>
            <button id="send_msg_btn">Send</button>
        </form>
    </div>
</body>
</html>
<script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>
<script src="{{URL::asset('/js/weixin/fofa.js')}}"></script>