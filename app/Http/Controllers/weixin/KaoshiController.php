<?php
namespace App\Http\Controllers\weixin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp;
use App\Model\WeixinUser;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
class KaoshiController extends Controller{
    protected $redis_weixin_access_token = 'str:weixin_access_token';
    function validToken1(){
        echo $_GET['echostr'];
    }
    /*
     * 推动事件
     */
    function wxtd(){
        $date = file_get_contents("php://input");       //file_get_contents() 把整个文件读入一个字符串中

        //解析XML
        $xml = simplexml_load_string($date);        //将 xml字符串 转换成对象
        $openid = $xml->FromUserName;             //用户openid
        $event = $xml->Event;                       //事件类型
        //当用户发送信息时，会自动回复一样的信息。
        if(isset($xml->MsgType)){                   //MsgType是类型
            if($xml->MsgType=='event'){                //判断事件类型
                if($event=='subscribe'){                    //如果$event等于此字符串
                    $sub_time = $xml->CreateTime;               //扫码关注时间

                    echo 'openid: '.$openid;echo '</br>';
                    echo '$sub_time: ' . $sub_time;

                    //获取用户信息
                    $user_info = $this->getUserInfo($openid);
                    echo '<pre>';print_r($user_info);echo '</pre>';

                    //保存用户信息
                    $u = WeixinUser::where(['openid'=>$openid])->first();
                    //var_dump($u);die;
                    if($u){       //用户不存在
                        echo '用户已存在';
                    }else{
                        $user_data = [
                            'openid'            => $openid,
                            'add_time'          => time(),
                            'nickname'          => $user_info['nickname'],
                            'sex'               => $user_info['sex'],
                            'headimgurl'        => $user_info['headimgurl'],
                            'subscribe_time'    => $sub_time,
                        ];

                        $id = WeixinUser::insertGetId($user_data);      //保存用户信息
                        var_dump($id);
                    }
                    echo  $this->huifu($openid,$xml->ToUserName);
                } elseif($event=='CLICK'){
                    echo  $this->huifu($openid,$xml->ToUserName);
                }
            }
        }
        //file_get_contents() 函数把整个文件读入一个字符串中。
        //file_put_contents() 函数把一个字符串写入文件中。
        $log=date('Y-m-d H:i:s')."\n".$date."\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log,FILE_APPEND);
    }
    public function huifu($openid,$from){
        $xml= '<xml>
                <ToUserName><![CDATA['.$openid.']]></ToUserName>
                <FromUserName><![CDATA['.$from.']]></FromUserName>
                <CreateTime>'.time().'</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA['. 'Hello 欢迎━(*｀∀´*)ノ亻!, 现在时间'. date('Y-m-d H:i:s') .']]></Content>
                </xml>';
        return $xml;
    }
    public function weixinlist(){
        return view('weixin.list');
    }
    /*
     * 推动事件日志
     */
    public function wxtd1(){
        $data=file_get_contents("php://input");
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/weixin_event.log',$log_str,FILE_APPEND);
    }
    /*
     * access_token
     */
    public function access_token(){
        $token=Redis::get($this->redis_weixin_access_token);
        if(!$token){
            $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $info=json_decode(file_get_contents($url),true);
            $token=$info['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;
    }
    /*
     * 获取用户信息
     */
    public function getUserinfo($openid){
        $access_token=$this->access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $info=json_decode(file_get_contents($url),true);
        return $info;
    }
}
?>