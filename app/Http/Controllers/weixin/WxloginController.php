<?php
namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class WxloginController extends Controller{
    public function wxlogin(){
        return view('weixin.wxlogin');
    }
    public function index(){
        // 1 回调拿到 code (用户确认登录后 微信会跳 redirect )
        echo '<pre>';print_r($_GET);echo '</pre>';echo '<hr>';
        echo '<pre>';print_r($_POST);echo '</pre>';

        $code = $_GET['code'];          // code

        //2 用code换取access_token 请求接口

        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxb31f0e1caa435a82&secret=c5fbffe423596c91ac32e99e2b59645e&code='.$code.'&grant_type=authorization_code';
        $token_json = file_get_contents($token_url);
        $token_arr = json_decode($token_json,true);
        echo '<hr>';
        echo '<pre>';print_r($token_arr);echo '</pre>';

        $access_token = $token_arr['access_token'];
        $openid = $token_arr['openid'];

        // 3 携带token  获取用户信息
        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $user_json = file_get_contents($user_info_url);

        $user_arr = json_decode($user_json,true);
        echo '<hr>';
        echo '<pre>';print_r($user_arr);echo '</pre>';
    }
}
?>