<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
class CheckRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    private $_api_data = [];
    private $_black_key = 'black_list';

    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        //先获取接口的数据，需要先解密
        $this->_decrypt($request);

        //访问次数限制
        $data=$this->_checkApiAccessCount();
        if(!$data['status']==1000){
            return response($data);
        }

        //验证签名
        $data = $this->_checkClientSign($request);
        if(!$data['status']==1000){
            return response($data);
        }

        //把解密的数据传递到控制器
        $request->request->replace($this->_api_data);
//        var_dump($data);die;
        //判断签名是否正确
        if ($data['status'] == 1000) {
            $response=$next($request);
//            var_dump($data);die;
            return $response;
        } else {
            return response($data);
        }

    }

    /*
     * 使用对称加密方法对数据进行加密
     */
    private function _decrypt($request)
    {
        $data = $request->post('data');
//        var_dump($data);die;
        #数据不为空
        if (!empty($data)) {
            $dec_data = openssl_decrypt(
                $data,
                'AES-128-CBC',
                'password',
                false,
                '0614668812076688'
            );
            $this->_api_data = json_decode($dec_data, true);

            return response($this->_api_data);
        }
    }

    //验证签名
    private function _checkClientSign($request)
    {

        //如果私有变量值不为空
        if (!empty($this->_api_data)) {
            //获取当前所有的app_id和key
            $map = $this->_getAppIdKey();
// array_key_exists() 函数检查某个数组中是否存在指定的键名，如果键名存在则返回 true，如果键名不存在则返回 false。
            if (array_key_exists($this->_api_data['app_id'], $map)) {
                return [
                    'status' => 1,
                    'msg' => 'check sign fail',
                    'data' => []
                ];
            }
//            var_dump($this->_api_data);
//
//            var_dump($map);exit;
            //生成服务端签名
            ksort($this->_api_data);
            //http_build_query($this->_api_data );
            //变成字符串 拼接app_key
            $server_str = http_build_query($this->_api_data) . '&app_key=' . $map['app_key'];
//             var_dump($server_str);
//             var_dump($map);
//            var_dump($request['sign']) ;die;
            if (md5($server_str)!= $request['sign']) {
                return [
                    'status' => 2,
                    'msg' => 'check sign fail1',
                    'data' => []
                ];
            }
            return ['status' => 1000];
        }
    }

    //获取系统现有的appid和key
    private function _getAppIdKey()
    {
        //从数据库获得对应的数据
        return [
            'app_id' => md5(1),
            'app_key' => md5('594188')
        ];
    }

    /**
     * @return mixed
     * 获取当前调用接口的appid
     */
    private function _getAppId()
    {
//        return $this->_api_data['app_id'];
        return $app_id='123';
    }

    //接口防刷
    private function _checkApiAccessCount()
    {
        //获取appid
        $app_id = $this->_getAppId();
//        $app_id = '123';
        $black_key = $this->_black_key;
        //判断是否在黑名单中
        $join_black_name = Redis::zScore($black_key, $app_id);
        //不在黑名单
        if (empty($join_black_name)) {
            $this->_addAppIdAccessCount();
            return ['status' => 1000];
        } else {
            //判断是否超过30min
            if (time() - $join_black_name >= 30 * 60) {
                Redis::zRemove($black_key, $app_id);
                $this->_addAppIdAccessCount();
            } else {
                return [
                    'status' => 3,
                    'msg' => '暂时不能访问接口，请稍后再试',
                    'data' => []
                ];
            }
        }
    }

    /**
     * @return array
     * 记录appid对应的访问次数
     */

    public function _addAppIdAccessCount()
    {
        $count = Redis::incr($this->_getAppId());
        if ($count == 1) {
            Redis::Expire($this->_getAppId(), 60);
        }
        //大于等于100 加入黑名单
        if ($count >= 10) {
            Redis::zAdd($this->_black_key, time(), $this->_getAppId());
            Redis::del($this->_getAppId());
            return [
                'status' => 3,
                'msg' => '暂时不能访问接口，请稍后再试',
                'data' => []
            ];
        }
    }
}