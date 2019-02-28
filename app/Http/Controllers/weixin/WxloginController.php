<?php
namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\WxloginModel;
class WxloginController extends Controller{
    public function wxlogin(){
        return view('weixin.wxlogin');
    }
    /*
     * 接收code
*/
    public function getCode(){
        echo '<pre>';print_r($_GET);echo '</pre>';
        $code=$_GET['code'];
        echo 'code:'.$code;
    }
    public function index(){
        // 1 回调拿到 code (用户确认登录后 微信会跳 redirect )
        echo '<pre>';print_r($_GET);echo '</pre>';echo '<hr>';
        echo '<pre>';print_r($_POST);echo '</pre>';

        $code = $_GET['code'];          // code

        //2 用code换取access_token 请求接口

        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxe24f70961302b5a5&secret=0f121743ff20a3a454e4a12aeecef4be&code='.$code.'&grant_type=authorization_code';
        $token_json = file_get_contents($token_url);
        $token_arr = json_decode($token_json,true);
        echo '<hr>';
//        echo '<pre>';print_r($token_arr);echo '</pre>';

        $access_token = $token_arr['access_token'];
        $openid = $token_arr['openid'];

        // 3 携带token  获取用户信息
        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $user_json = file_get_contents($user_info_url);

        $user_arr = json_decode($user_json,true);
        echo '<hr>';
//        echo '<pre>';print_r($user_arr);echo '</pre>';
        //查询数据库是否有此人数据
        $pat=WxloginModel::where(['unionid'=>$user_arr['unionid']])->first();
        if($pat['unionid']==$user_arr['unionid']){
            return redirect('/centeradd');
        }else {
            $data = [
                'nickname' => $user_arr['nickname'],
                'sex' => $user_arr['sex'],
                'country' => $user_arr['country'],
                'unionid' => $user_arr['unionid'],
                'openid' => $user_arr['openid'],
                'access_token' => $token_arr['access_token'],
                'add_time' => time()
            ];
//        var_dump($data);die;
            $res = WxloginModel::insertGetId($data);
//        var_dump($res);die;
            if ($res) {
                echo '登录入库成功';
            }
        }
    }
}
?>