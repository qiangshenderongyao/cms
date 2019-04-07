<?php
namespace App\Http\Controllers\test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Model\GoodsModel;
use App\Model\KsModel;
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
//        echo '<pre>';print_r($_POST);echo '</pre>';
        $cname=request()->post('username');
        $password=request()->input('password');
//        $ip=request()->input('ip');
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
                if(($key!==$sss)==true){
                    echo '此用户已在登录';
//                    session_destroy();//清除SESSION值.
                    return redirect('http://1807.96myshop.cn/test/one');
                }
                Redis::set($redis_key_web_token,$token);
                Redis::expire($redis_key_web_token,86400);
                $reponse=[
                    'status'=>200,
                    'msg'=>'登录成功',
                    'token'=>$token
                ];
                echo  json_encode($reponse);
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
//        $ip=request()->input('ip');
        $data=[
            'username'=>$cname,
            'password'=>$password,
        ];
        $url="http://1807.96myshop.cn/test/one";
        $ch=curl_init();    //创建新的curl资源
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $res=curl_exec($ch);     //接收响应
        return $res;
//        var_dump($res);
//        $response=json_decode($res,true);
//        return $response;
    }
    /*
     * 首页
     */
    public function startest(){
        $data=[
            'username'=>'',
            'password'=>''
        ];
        $url="http://1807.96myshop.cn/startest/onstart";
        $ch=curl_init();    //创建新的curl资源
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $res=curl_exec($ch);     //接收响应
        return $res;
    }
    public function onestart(){
        $data=GoodsModel::all();
//        var_dump($data);die;
        if(!$data){
            echo '商品不存在';exit;
        }
        echo $data;
        $data=file_get_contents("php://input");
        //记录日志
        $log_str=date('Y-m-d H:i:s')."\n".$data."\n<<<<<<<";
        file_put_contents('logs/test_one.log',$log_str,FILE_APPEND);
    }
    /*
     * 2019年4月2日08:49:23
     * 考试
     */
    public function kslogin(){
        return view('ks.kslogin');
    }
    public function ksloginadd(Request $request){
        $info=request()->post();
        $sname=$info['sname'];
        $shenfen=$info['shenfen'];
//        $file=$info['file'];
//        $file=$this->upload_img($file);
        $yt=$info['yt'];
        $where=[
            'sname'=>$sname,
            'shenfen'=>$shenfen,
//            'file'=>$file,
            'yt'=>$yt
        ];
//        var_dump($where);die;
        $data=KsModel::insert($where);
//        var_dump($data);
    }
    //文件上传
    public function upload(){
        $file=request()->file('file');// 获取表单上传文件 例如上传了001.jpg
//        var_dump($file);die;
        // 移动到框架应用根目录/public/uploads/goods 目录下
        $info=$file->move(ROOT_PATH.'public'.DS.'uploads'.DS.'goods');
        $path='./uploads/goods/'.$info->getSaveName();//
        $filename=$info->getFilename();//原文件名字
        return ['path'=>$path,'filename'=>$filename];//返回图片路径、文件名称
    }

    function upload_img($file)
    {
        $url_path = 'uploads/img';
        $rule = ['jpg', 'png', 'gif'];
        if ($file->isValid()) {
            $clientName = $file->getClientOriginalName();
            $tmpName = $file->getFileName();
            $realPath = $file->getRealPath();
            $entension = $file->getClientOriginalExtension();
            if (!in_array($entension, $rule)) {
                return '图片格式为jpg,png,gif';
            }
            $newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $entension;
            $path = $file->move($url_path, $newName);
            $namePath = $url_path . '/' . $newName;
            return $path;
        }
    }
    public function fafang(){
        $id=request()->get('id');
        $status=KsModel::where('id',$id)->first()->toArray();
        $status=$status['status'];
        $app_key=$status['app_key'];
        $app_secret=$status['app_secret'];
//        var_dump($status);die;
        if($status==2){
            echo '你未通过';die;
        }else if($status==0){
            echo '你未通过';die;
        }else if(empty($app_key)){
            echo '您已获取';
        }else{
            $app_key= rand(11111,99999) . rand(2222,9999);
            $str='0123456789abcdefghijklmnopqrstuvwxyz+-*/';
            $app_secret=substr(str_shuffle($str),rand(1,20),16);
            $where=[
                'app_key'=>$app_key,
                'app_secret'=>$app_secret
            ];
//        var_dump($where);die;
            $data=KsModel::where('id',$id)->update($where);
            if($data){
                return 'app_key与app_secret发放成功';
            }
        }

    }
    public function kslist(){
        $data=KsModel::paginate(2);
        return view('ks.kslist',['data'=>$data]);
    }
    public function fbnq(){
        $n=20;
        $array[1] = $array[0] = 1; //设第一个值和第二个值为1
        for($i=2;$i<$n;$i++){ //从第三个值开始
            $array[$i] = $array[$i-1] + $array[$i-2];
            //后面的值都是当前值的前一个值加上前两个值的和
        }
        echo '<pre>';
        print_r($array);
        echo '<pre>';
    }
    public function bili(){
        $a=array(1,2,3,4,5,6,7);
        foreach($a as $k=>$v) {
            if ($v % 2 == 1) {
                $data[$k][] = $v;
            } else {
                $data[$k - 1][] = $v;
            }
        }
        print_r($data);
    }
    /*
     * 用户展示
     */
    public function testlist(){
        $data=DB::table('testuser')->select();
        return view('test.testlist',['data'=>$data]);
    }
}
?>