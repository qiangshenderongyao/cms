<?php
namespace App\Http\Controllers\test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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
        $res=json_encode($info);
        if(!empty($res['username'])&&!empty($res['password'])){
            $where=['username'=>$res['username'],'password'=>$res['password']];
            $data=DB::table('testuser')->where($where)->first()->toArray();
            if($res['username']==$data['username']&&$res['password']==$data['password']){
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