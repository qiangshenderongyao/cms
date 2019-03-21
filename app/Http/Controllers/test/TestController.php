<?php
namespace App\Http\Controllers\test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            $where=['username'=>$info['username'],'password'=>$info['password']];
            $data=DB::table('testuser')->where($where)->first()->toArray();
            if($info['username']==$data['username']&&$info['password']==$data['password']){
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
}
?>