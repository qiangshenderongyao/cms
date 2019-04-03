<?php
namespace App\Http\Controllers\test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Model\HBModel;
class KsController extends Controller{
    public function login(){
        $account = $_POST['account'];
        $password = $_POST['password'];
        $data = [
            'account'	=>	$account,
            'password'	=>	$password
        ];
        $url = 'http://1807.96myshop.cn/kkss/loginadd';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $res = curl_exec($ch);
        return $res;
    }
    //login
    public function loginadd(){
        $account = $_POST['account'];
        $password = md5($_POST['password']);
        $res = HBModel::orwhere(['name'=>$account])->orwhere(['email'=>$account])->orwhere(['tel'=>$account])->first();
        //print_r($res);die;
        if($res){
            if($password==$res['password']){
                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                $id = $res['id'];
                $redis_token_key = "str:hb_u_token".$id;
                $arr = Redis::set($redis_token_key,$token);
                //print_r($arr);die;
                Redis::expire($redis_token_key,3600);
                $response = [
                    'error' =>  0,
                    'msg'   => 'ok',
                    'uid'    =>  $id,
                    'name'  =>  $account,
                    'token' =>  $token
                ];
            }else{
                $response = [
                    'error' =>  500,
                    'msg'   =>  'please check out your pwd'
                ];
            }
        }else{
            $response = [
                'error' =>  500,
                'msg'   =>  'account not found'
            ];
        }
        return $response;
    }
}