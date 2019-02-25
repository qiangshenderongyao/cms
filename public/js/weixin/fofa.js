//获取值
var openid=$('#openid').val();
//setInterval() 方法可按照指定的周期（以毫秒计）来调用函数或计算表达式。
setInterval(function(){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url     :   '/weixin/wxfofa?openid='+openid+'&pos='+$("#msg_pos").val(),
        type    :   'get',
        dataType:   'json',
        success :   function(d){
            // console.log(d);
            if(d.errno==0){
                var msg_str='<blockquote>'+d.data.add_time+'<p>'+d.data.text+'</p>'+'</blockquote>';
                $("#chat_div").append(msg_str);
                $("#msg_pos").val(d.data.id);
            }else{
                // alert(d.msg);
            }
        }
    });
},5000);
//客服发送信息
$("#send_msg_btn").click(function (y) {
    y.preventDefault();
    var send_msg= $("#send_msg").val().trim();
    var msg_str = '<p style="color: mediumorchid"> >>>>> '+send_msg+'</p>';
    $("#chat_div").append(msg_str);
    $("#send_msg").val("");
});