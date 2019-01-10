//给按钮一个点击事件
$('#add_cart_btn').click(function (data) {
    //preventDefault() 方法阻止元素发生默认的行为（例如，当点击提交按钮时阻止对表单的提交）。
    data.preventDefault();
    //获取值
    var goods_num=$('#goods_num').val();
    var goods_id=$('#goods_id').val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url     :   '/add2',
        type    :   'post',
        data    :   {goods_id:goods_id,num:goods_num},
        dataType:   'json',
        success :   function(d){
            if(d.error==301){
                window.location.href=d.url;
            }else{
                alert(d.msg);
            }
        }
    });
});
