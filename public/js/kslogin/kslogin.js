layui.use('upload', function(){
    var upload = layui.upload;
    var tag_token = $(".tag_token").val();
    //普通图片上传
    var uploadInst = upload.render({
        elem: '.btn_upload_img'
        ,type : 'images'
        ,exts: 'jpg|png|gif' //设置一些后缀，用于演示前端验证和后端的验证
        //,auto:false //选择图片后是否直接上传
        //,accept:'images' //上传文件类型
        ,url: 'upload.php'
        ,data:{'_token':tag_token}
        ,before: function(obj){
            //预读本地文件示例，不支持ie8
            obj.preview(function(index, file, result){
                $('.img-upload-view').attr('src', result); //图片链接（base64）
            });
        }
        ,done: function(res){
            //如果上传失败
            if(res.status == 1){
                return layer.msg('上传成功');
            }else{//上传成功
                layer.msg(res.message);
            }
        }
        ,error: function(){
            //演示失败状态，并实现重传
            return layer.msg('上传失败,请重新上传');
        }
    });
});