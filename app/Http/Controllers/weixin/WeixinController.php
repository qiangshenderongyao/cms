<?php
namespace App\Http\Controllers\weixin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\WeixinUser;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp;
use Illuminate\Support\Facades\Storage;

class WeixinController extends Controller{
    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token
    //测试
    public function test()
    {
        $this->getUserInfo(1);
    }

    //首次接入
    function validToken1(){
        echo $_GET['echostr'];
    }
    //接收推送事件
    function weixinEven(){
        $data = file_get_contents("php://input");

        //解析XML
        $xml = simplexml_load_string($data);        //将 xml字符串 转换成对象
        $openid = $xml->FromUserName;             //用户openid
        $event = $xml->Event;                       //事件类型
        //当用户发送信息时，会自动回复一样的信息。
        if(isset($xml->MsgType)){
            if($xml->MsgType=='text'){              //用户发送文本信息
                $msg=$xml->Content;
                $xml_response='<xml>
                     <ToUserName><![CDATA['.$openid.']]></ToUserName>
                     <FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName>
                     <CreateTime>'.time().'</CreateTime>
                     <MsgType><![CDATA[text]]></MsgType>
                     <Content><![CDATA['. $msg.']]></Content>
                     </xml>';
                echo $xml_response;
            }elseif($xml->MsgType=='image'){       //用户处理图片
                if(1){
                    $this->images($xml->MediaId);
                    $xml_response='<xml>
                        <ToUserName><![CDATA['.$openid.']]></ToUserName>
                        <FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName>
                        <CreateTime>'.time().'</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA['. str_random(10) . ' >>> '.']]></Content>
                        </xml>';
                    echo $xml_response;
                }
            }elseif($xml->MsgType=='voice'){        //处理语音
                echo $this->voice($xml->MediaId);
            }elseif($xml->MsgType=='event'){                //判断事件类型
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
                } elseif($event=='CLICK'){
                    echo  $this->kefu01($openid,$xml->ToUserName);
                }
            }
        }
        //file_get_contents() 函数把整个文件读入一个字符串中。
        //file_put_contents() 函数把一个字符串写入文件中。
        //$log=date('Y-m-d H:i:s')."\n".$data."\n<<<<<<<";
        //file_put_contents('logs/wx_event.log',$log,FILE_APPEND);
    }

    /**
     * 客服处理
     * $openid 用户id
     * $from   开发者公众号id
     */
    public function kefu01($openid,$from){

        $xml= '<xml>
                <ToUserName><![CDATA['.$openid.']]></ToUserName>
                <FromUserName><![CDATA['.$from.']]></FromUserName>
                <CreateTime>'.time().'</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA['. 'Hello php, 现在时间'. date('Y-m-d H:i:s') .']]></Content>
                </xml>';
            return $xml;

    }
    /**
     * 接收事件推送
     */
    public function validToken()
    {
        $data = file_get_contents("php://input");
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
    }
    public function getWXAccessToken()
    {
        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;

    }
    /**
     * 获取微信AccessToken
     */
    public function WXAccessToken()
    {

        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;

    }
    /**
     *接收图片素材
     */
    public function images($media_id){
        $access_token = $this->WXAccessToken();
        $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
        $client=new GuzzleHttp\Client();
        $response=$client->get($url);
        //找到文件名路径
        $file_info=$response->getHeader('Content-disposition');
        $file_name=substr(rtrim($file_info[0],'""'),-20);
        $wx_imgage_put='wx/images/'.$file_name;
        //保存其路径
        $lujing=Storage::disk('local')->put($wx_imgage_put,$response->getBody());
        if($lujing){
            echo '保存成功';
        }else{
            echo '保存失败';
        }
    }
    /*
     * 接收语音素材
     */
    public function voice($media_id){
        $access_token = $this->WXAccessToken();
        $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
        $client=new GuzzleHttp\Client();
        $response=$client->get($url);
        //找到文件名路径
        $file_info=$response->getHeader('Content-disposition');
        $file_name=substr(rtrim($file_info[0],'""'),-20);
        $wx_imgage_put='wx/voice/'.$file_name;
        //保存其路径
        $lujing=Storage::disk('local')->put($wx_imgage_put,$response->getBody());
        if($lujing){
            return  '保存成功';
        }else{
            return  '保存失败';
        }
    }
    /**
     * 获取用户信息
     * @param $openid
     */
    public function getUserInfo($openid)
    {
        //$openid = 'oLreB1jAnJFzV_8AGWUZlfuaoQto';
        $access_token = $this->WXAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

        $data = json_decode(file_get_contents($url),true);
        //echo '<pre>';print_r($data);echo '</pre>';
        return $data;
    }
    /**
     * 服务号创建菜单
     */
    public function create(){
        //1、获取access_token，拼接微信接口
        $access_token=$this->getWXAccessToken();
        $url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
        //2、请求微信接口
        $client=new GuzzleHttp\Client(['base_uri'=>$url]);
//        $data = [
//            "button"    => [
//                [
//                    "type"  => "view",      // view类型 跳转指定 URL
//                    "name"  => "宠物乐园",
//                    "url"   => "https://www.sougou.com"
//                ]
//            ]
//        ];
        $data=[
            "button"=>[
                ["name"=>"相机",
                    "sub_button"=>[
                        [
                            "type"=>"scancode_waitmsg",
                            "name"=>"扫码",
                            "key"=>"rselfmenu_0_0",
                            "sub_button"=>[]
                        ],
                        [
                            "type"=>"scancode_push",
                            "name"=>"扫码推事件",
                            "key"=>"rselfmenu_0_1",
                            "sub_button"=>[]
                        ]
                    ]
                ],
                ["name"=>"发图",
                    "sub_button"=>[
                        [
                            "type"=>"pic_sysphoto",
                            "name"=>"系统拍照发图",
                            "key"=>"rselfmenu_1_0",
                            "sub_button"=>[]
                        ],
                        [
                            "type"=>"pic_photo_or_album",
                            "name"=>"拍照或相册发图",
                            "key"=>"rselfmenu_1_1",
                            "sub_button"=>[]
                        ]
                    ]
                ],
                [
                    "type"  => "click",      // click类型
                    "name"  => "时间",
                    "key"   => "kefu01"
                ]
            ]
        ];
        //JSON_UNESCAPED_UNICODE转中文
        $r=$client->request('POST',$url,[
            'body'=>json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);
        //3、解析接口返回信息
        $response_arr = json_decode($r->getBody(),true);
        if($response_arr['errcode']==0){
            echo '菜单创建成功';
        }else{
            echo '菜单创建失败';
            echo $response_arr['errmsg'];
        }
    }
}