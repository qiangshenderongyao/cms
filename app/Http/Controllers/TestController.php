<?php
namespace App\Http\Controllers;
use App\Model\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
/**
 * Created by PhpStorm.
 * User: 严世钰
 * Date: 2018/11/6
 * Time: 11:53
 */
class TestController extends Controller{
    public  function test(){

//        return redirect()->route('index',[90]);
//        echo route('a');
        return view('1805.test',['name'=>'枪神'])->with(['title'=>'测试','show_footer'=>1]);
    }
    public  function  add(){
        $info=request()->all('cname');
        if(empty($info)){
            echo '姓名不能为空';
        }
        $data=DB::table('ceshi')->insert($info);
        return redirect('add_list');
    }
    public  function  add_list(){
        $data = DB::table('ceshi')->get();
        return view('add_list',['data'=>$data]);
    }
    public  function delete(){
        $info=request()->get('uid');
        $res=DB::table('ceshi')->delete($info);
        if($res){
            echo '删除成功';
        }else{
            echo '删除失败';
        }
    }
    public  function  update(){
        $info=request()->get('uid');
        $res=DB::table('ceshi')->find($info);
        return view('update')->with('res',$res);
    }
    public  function  update_add(){
        $id=request()->all('uid');
        $info=request()->all('cname');
        $res=DB::table('ceshi')->where('id',$id)->update($info);
        return redirect('add_list');
    }
    public function checkCookie()
    {
        echo __METHOD__;
    }
}
?>