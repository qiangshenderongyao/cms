<?php

namespace App\Http\Controllers\Vip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Model\Test;
class IndexController extends Controller
{
    public function zhu(){
        return view('register.register');
    }
    public function zhuce(Request $request){
        $cname=request()->post('cname');
        $password=request()->post('password');
        $pwd=request()->post('pwd');
        if(empty($password)){
            echo '密码不能为空';
        }else if($password!=$pwd){
            echo '密码和确认密码不一致';
        }
        $name=Test::where(['cname'=>$cname])->first();
        if($name){
            echo '用户名已存在';die;
        }
        //password_hash密码加密
        $pass = password_hash($password,PASSWORD_BCRYPT);
        $info = [
            'cname'  => request()->post('cname'),
            'age'  => request()->post('age'),
            'ctime'  => time(),
            'password'  => $pass
        ];
        $data=DB::table('ceshi')->insertGetId($info);
        if($data){
           setcookie('uid',$data,time()+86400,'/','',false,true);
           return redirect('/center');
           echo '注册成功,登录中....';
        }else{
           echo '注册失败';
           return redirect('/zhu');
        }
    }
    public function login(){
        return view('login.login');
    }
    public function loginadd(Request $request){
        echo '<pre>';print_r($_POST);echo '</pre>';
        $cname=request()->post('cname');
        $password=request()->input('password');
        $res=DB::table('ceshi')->where(['cname'=>$cname])->first();
//         var_dump($res);die;
        if($res){
            //password_verify密码解密 接收密码和数据库表中密码
            if( password_verify($password,$res->password) ){
                //substr(字符串,开始位置,长度);
                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                //名称,值,有效期,服务器路径,域名,安全。
                setcookie('uid',$res->uid,time()+86400,'/','',false,true);
                setcookie('token',$token,time()+86400,'/','',false,true);
                // dump($token);die;
                $request->session()->put('u_token',$token);
                $request->session()->put('uid',$res->uid);
                $redis_key_web_token='str:uid:token:'.$res->unid;
                Redis::del($redis_key_web_token);
                Redis::hset($redis_key_web_token,'web',$token);
                echo '登录成功';
                 return redirect('/center');die;
            }else{
                echo '登录失败';
                // return redirect('/login');die;
            }
        }else{
            echo("用户不存在");die;
        }
    }
    public function ce(){
        return view('1805.ce');
    }
    public function center(Request $request){
//        var_dump($_COOKIE);die;
        //如果cookie和session里的值
//        if($_COOKIE['token']!=$request->session()->get('u_token')){
//            die('非法请求');
//        }else{
//            echo '正常请求';
//        }
//        echo 'u_token:'.$request->session()->get('u_token');echo '</br>';
//        echo '<pre>';print_r($_COOKIE);echo '</pre>';
//        return redirect('/cart');
//        die;
        if(empty($_COOKIE['uid'])){
            return redirect('/mylogin');
            echo '请先登录';
            exit;
        }else{
            echo 'UID: '.$_COOKIE['uid'] . ' 欢迎回来';
        }
    }
    public function centeradd(){
        return view('Vip.center');
    }
}