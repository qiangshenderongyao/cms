<?php
namespace App\Http\Controllers\weixin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
class WeixinController extends Controller{
    //首次接入
    function validToken1(){
        echo $_GET['echostr'];
    }
    //接收推送事件
    function weixinEven(){
        //file_get_contents() 函数把整个文件读入一个字符串中。
        //file_put_contents() 函数把一个字符串写入文件中。
        $data=file_get_contents("php:input");
        $log=date('Y:m:d H:i:s')."\n".$data."\n<<<<<";
        file_put_contents('logs/wx_event.log',$log,FILE_APPEND);
    }
}