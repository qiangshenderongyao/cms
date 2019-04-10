<?php
namespace App\Http\Controllers\Ce;
use App\Model\Test;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
/**
 * Created by PhpStorm.
 * User: 严世钰
 * Date: 2018/11/6
 * Time: 11:53
 */
class CeController extends Controller {
    public function ceshi(Request $request){
//        var_dump($request->post('name'));
        return ['status'=>1000,'msg'=>'success','data'=>[]];
    }
    public function updateimg(Request $request){
        if(empty($request->post('content'))){
            return ['status'=>5,'data'=>[],'msg'=>'上传的文件内容不能为空'];
        }
        $date=date('Ym');
        //指定文件存储路径
        $file_save_path=app_path().'/storage/uploads/'.$date.'/';
        if(!is_dir($file_save_path)){
            mkdir($file_save_path,0777,true);
        }
        $file_name=time().rand(1000,9999).'.tmp';
        $byte=file_put_contents($file_save_path.$file_name,base64_decode($request->post('content')));
        if($byte>0){
            #查看文件格式
            $info=getimagesize($file_save_path . $file_name);
//            var_dump($info);die;
            if(!$info){
                return ['status'=>6,'data'=>[],'msg'=>'图片格式不正确'];
            }
        }
        #判断图片格式
        switch($info['mime']){
            case 'image/jpeg';
                $new_file_name=str_replace('tmp','jpg',$file_name);
                break;
            case 'image/png';
                $new_file_name=str_replace('tmp','png',$file_name);
                break;
            default;
                return ['status'=>7,'data'=>[],'msg'=>'图片格式不正确'];
                break;
        }
        #文件命名
        rename($file_save_path.$file_name,$file_save_path.$new_file_name);
        $api_response=[];
//        $api_response['access_path']='http://96cmstu.cn/'.$date.'/'.$new_file_name;
        $api_response['access_path']='http://1807.96myshop.cn/'.$date.'/'.$new_file_name;
        return ['status'=>1000,'data'=>$api_response,'msg'=>'success'];
    }
    /*
     * 验证码
     */
    public function showVcode(){
        session_start();
        $rand=rand(1000,9999);
        header('content-type:image/png');
        #创建画布
        $im=imagecreatetruecolor(400,30);
        #创建颜色
        $white=imagecolorallocate($im,255,255,255);

    }
}
?>