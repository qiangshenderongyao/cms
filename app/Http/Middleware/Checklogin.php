<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Redis;
use Closure;
class Checklogin{
    public function handle($request,Closure $next){
        if(isset($_COOKIE['unid'])&&isset($_COOKIE['token'])){
            //验证token
            $key='str:u:token:'.$_COOKIE['unid'];
            $token=Redis::get($key);
            if($_COOKIE['token']==$token){
                //token有效
                $request->attributes->add(['is_login'=>1]);
            }else{
                //token无效
                $request->attributes->add(['is_login'=>0]);
            }
            return $next($request);
        }
    }
}