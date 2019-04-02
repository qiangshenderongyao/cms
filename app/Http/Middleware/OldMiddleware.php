<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Redis;
use Closure;

class OldMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request_url=$_SERVER['REQUEST_URI'];   //访问方法
        $hash=substr(md5($request_url),0,10);   //加密
//        var_dump($_SERVER);//打印，找到信息
        $REMOTE_ADDR=$_SERVER['REMOTE_ADDR'];   //客户地址ip
        $redis_key='str:'.$hash.':'.$REMOTE_ADDR;
        $number=Redis::incr($redis_key);    //若值不为空,incr命令会解释为十进制64位有符号整数
        Redis::expire($redis_key,60);       //时间限制  设置过期时间
        if($number>5){     //若刷新次数超过5次,则视为非法请求
            $response=[
                'errno' => 4003,
                'msg'   => 'Invalid Request!!',
            ];
            Redis::expire($redis_key,10);       //限制时间，并将存入Redis
            $fei_request_ip='s:Invalid:ip:';
            Redis::sAdd($fei_request_ip,$REMOTE_ADDR);  //将一个或多个元素加入集合中
            return json_encode($response);
        }

        return $next($request);
    }
}