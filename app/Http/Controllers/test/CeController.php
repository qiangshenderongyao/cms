<?php
namespace App\Http\Controllers\test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
class CeController extends Controller{
    public function ce($request,Closure $next){
        //获取接口的数据，需先解密
        $this->_decrypt($request);
        //验证签名
        $data=$this->_checkClientSign($request);
        //判断签名是否正确
//        return $next($request);
    }
    /*
     * 使用对称加密方法对数据进行加密
     */
    public function _decrypt($request){
        $data = $request->post('data');
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
    /*
     * 验证签名
     */
    public function _checkClientSign($request){
        if (!empty($this->_api_data)) {
            //获取当前所有的app_id和key
            $map = $this->_getAppIdKey();
            if (array_key_exists($this->_api_data['app_id'], $map)) {
                return [
                    'status' => 1,
                    'msg' => 'check sign fail',
                    'data' => []
                ];
            }
            //var_dump($map);exit;
            //生成服务端签名
            ksort($this->_api_data);
            http_build_query($this->_api_data);
            //变成字符串 拼接app_key
            $server_str = http_build_query($this->_api_data . '&app_key=' . $map[$this->_api_data['app_id']]);
            if (md5($server_str) != $request['sign']) {
                return [
                    'status' => 2,
                    'msg' => 'check sign fail1',
                    'data' => []
                ];
            }
            return ['status' => 1000];

        }
    }
    /*
     * 获取系统现有的appid和key
     */
    private function _getAppIdKey()
    {
        //从数据库获得对应的数据
        return [
            'api_id' => md5(0540),
            'api_key' => md5('2300540')
        ];
    }
}
?>