//给按钮一个点击事件
$('.btn-info').click(function (data) {
    //preventDefault() 方法阻止元素发生默认的行为（例如，当点击提交按钮时阻止对表单的提交）。
    data.preventDefault();
    //获取值
    var goods_num=$('#goods_num').val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url     :   '/Redisbuy',
        type    :   'post',
        data    :   {num:goods_num},
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