<?php
namespace App\Http\Controllers\test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller{
    public function test1(){
        $data=[
            'name'=>'枪神',
            'age'=>20
        ];
       echo json_encode($data);
    }
    public function test2(Request $request){
        $info=$request->all();
        if(!empty($info['username'])&&!empty($info['password'])){
            $where=['password'=>$info['password']];
            $dada=DB::table('testuser')->where($where)->first();
            if($info['username']==$dada->username&&$info['password']==$dada->password){
                echo 'success';
            }else{
                echo 'error-no';
            }
        }
        $data=file_get_contents("php://input");
        //记录日志
        $log_str=date('Y-m-d H:i:s')."\n".$data."\n<<<<<<<";
        file_put_contents('logs/test_api2.log',$log_str,FILE_APPEND);
    }
    public function start(){
        $redirect=$_GET['redirect'] ?? env('SHOP_URL');//上一级路径
        $data=['redirect'=>$redirect];
        return view('test.start',$data);
    }
    public function str(Request $request){
        echo '<pre>';print_r($_POST);echo '</pre>';
        $cname=request()->post('username');
        $password=request()->input('password');
        $redirect=$request->input('redirect') ?? env('SHOP_URL');
        $where=['username'=>$cname];
        $data=DB::table('testuser')->where($where)->first();
        if($data){
            //password_verify密码解密 接收密码和数据库表中密码
            if( password_verify($password,$data->password) ){
                //substr(字符串,开始位置,长度);
                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                //名称,值,有效期,服务器路径,域名,安全。
                setcookie('unid',$data->unid,time()+86400,'/','',false,true);
                setcookie('token',$token,time()+86400,'/','',false,true);
                // dump($token);die;
                $request->session()->put('u_token',$token);
                $request->session()->put('unid',$data->unid);
                //记录web登录token
                $redis_key_web_token='str:uid:token:'.$data->unid;
                Redis::set($redis_key_web_token,$token);
                Redis::expire($redis_key_web_token,86400);
                header("Refresh:3;url=".$redirect);
                echo '登录成功';
                // return redirect('/center');die;
            }else{
                echo '登录失败';
                // return redirect('/login');die;
            }
        }else{
            echo("用户不存在");die;
        }
    }
    public function one(Request $request){
        echo '<pre>';print_r($_POST);echo '</pre>';
        $cname=request()->post('username');
        $password=request()->input('password');
        $redirect=$request->input('redirect') ?? env('SHOP_URL');
        $where=['username'=>$cname];
        $data=DB::table('testuser')->where($where)->first();
//        var_dump($data);die;
        if($data){
            //password_verify密码解密 接收密码和数据库表中密码
            if( password_verify($password,$data->password) ){
                //substr(字符串,开始位置,长度);
                session_start();
                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                //名称,值,有效期,服务器路径,域名,安全。
                setcookie('unid',$data->unid,time()+86400,'/','',false,true);
                setcookie('token',$token,time()+86400,'/','',false,true);
                // dump($token);die;
                $request->session()->put('u_token',$token);
                $request->session()->put('unid',$data->unid);
                //记录web登录token
                $redis_key_web_token='str:uid:token:'.$data->unid;
                $ss=rand(1,100);
                $ssp=Redis::hgetall($redis_key_web_token);
                foreach($ssp as $k=>$v){
                    $key=$ssp[$k];
                }
                echo $key;echo '<hr>';
                Redis::del($redis_key_web_token);
                Redis::hset($redis_key_web_token,'Android'.$ss,$token);
                $sss=Redis::hget($redis_key_web_token,'Android'.$ss);
//                if(($key!==$sss)==true){
//                    echo '此用户已在登录';
//                    session_destroy();//清除SESSION值.
//                    return redirect('http://1807.96myshop.cn/test/one');
//                    die;
//                }
//                Redis::set($redis_key_web_token,$token);
//                Redis::expire($redis_key_web_token,86400);
                $reponse=[
                    'status'=>200,
                    'msg'=>'登录成功',
                    'token'=>$token
                ];
                echo $reponse;
//                echo  json_encode($reponse);
            }else{
                echo '登录失败';
                // return redirect('/login');die;
            }
        }else{
            echo("用户不存在");die;
        }
        $data=file_get_contents("php://input");
        //记录日志
        $log_str=date('Y-m-d H:i:s')."\n".$data."\n<<<<<<<";
        file_put_contents('logs/test_one.log',$log_str,FILE_APPEND);
    }
    public function testone(Request $request){
        $cname=request()->input('username');
        $password=request()->input('password');
        $data=[
            'username'=>$cname,
            'password'=>$password
        ];
        $url="http://1807.96myshop.cn/test/one";
        $ch=curl_init();    //创建新的curl资源
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $res=curl_exec($ch);     //接收响应
//        return $res;
        var_dump($res);
//        $response=json_decode($res,true);
//        return $response;
    }
}
?>