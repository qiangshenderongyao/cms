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
    public function wxtd(){
        $info=file_get_contents("php://input");
        $xml=simplexml_load_string($info);
        $openid=$xml->FromUserName;     //  openID
        $event=$xml->Event;             //用户类型
        if(isset($xml->Msgtype)){
            if($xml->MsgType=='event'){     //关注
                if($event=='subscribe'){
                    $sub_time=$xml->CreateTime;     //关注时间
                    $user_info=$this->getUserinfo($openid);
                    $res=WeixinUser::where(['openid'=>$info])->first();
                    if($res){
                       echo '用户已存在';
                    }else{
                        $data=[
                            'openid'            => $openid,
                            'add_time'          => time(),
                            'nickname'          => $user_info['nickname'],
                            'sex'               => $user_info['sex'],
                            'headimgurl'        => $user_info['headimgurl'],
                            'subscribe_time'    => $sub_time,
                        ];
                        $date=WeixinUser::where($data)->get();
                    }
                    echo  $this->huifu($openid,$xml->ToUserName);
                }
            }
        }
        $log=date('Y-m-d H:i:s')."\n".$info."\n<<<<<<<";
        file_put_contents('logs/weixin_event.log',$log,FILE_APPEND);
        return redirect('/weixin/list');
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
        $data=file_put_contents("php://input");
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/weixin_event.log',$log_str,FILE_APPEND);
    }
    /*
     * access_token
     */
    public function access_token(){
        $access_token=Redis::get($this->redis_weixin_access_token);
        if(!$access_token){
            $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');;
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