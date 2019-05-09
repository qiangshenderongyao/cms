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
        $api_host='http://96cms.cn';
        session_start();
        $sid=session_id();
        $vcode_url=$api_host.'/ceshi/vcode?sid='.$sid;
        $data=[
            'url'=>$vcode_url,
            'sid'=>$sid
        ];
        return ['status'=>1000,'msg'=>'success','data'=>$data];
    }
    /*
     * 验证码算法
     */
    public function vcode(){
        $sid=request()->get('sid');
        session_id($sid);
        session_start();

        $a = rand(1,9);
        $b = rand(1,9);



        //加法
        $c = $a + $b ;
        $code = $a .'+'. $b .'=?';

        //乘法
//        $c = $a * $b ;
//        $code = $a .'*'. $b .'=?';

        //除法

//        $code = $a .'/'. $b .'=?';
//        $c = $a / $b ;
//        $a = $c * $b;

        $_SESSION['code'] = $c;

        #输出图片
        // Set the content-type
        header('Content-Type: image/png');

        // Create the image
        $im = imagecreatetruecolor(150, 30);

        // Create some colors
        $white = imagecolorallocate($im, 255, 255, 255);
        $grey = imagecolorallocate($im, 128, 128, 128);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 399, 29, $white);

        // The text to draw

        // Replace path by your own font path
        $font = '/home/wwwroot/default/cms/public/a.ttf';

        // Add some shadow to the text
        $i = 0;
        $len = strlen($code);
        while($i < $len){
            if(is_numeric($code[$i])){
                imagettftext($im, 20, rand(-45,45), $i*22+11, 25, $grey, $font, $code[$i]);

            }else{
                imagettftext($im, 20, 0, $i*22+11, 25, $grey, $font, $code[$i]);

            }
            $i++;
        }
        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($im);
        imagedestroy($im);
        exit;
    }
    /*
     * 接收
     */
    public function checkVcode(Request $request)
    {

        //跨域
        header("Access-Control-Allow-Origin:http://96cms.cn");

        $vcode = $_GET['vcode'];
        $callback = $_GET['callback'];
        $sid = $_GET['sid'];


        session_id($sid);
        session_start();

        if ($_SESSION['code'] == $vcode) {
            $res = ['status' => 1000, 'msg' => '验证码正确', 'data' => []];
            $res = json_encode($res);
            return $callback . "(" . $res . ")";
        } else {
            $res = ['status' => 1, 'msg' => '验证码错误', 'data' => []];
            $res = json_encode($res);
            return $callback . "(" . $res . ")";
        }
    }
}
?>