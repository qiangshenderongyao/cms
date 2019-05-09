<?php
namespace App\Http\Controllers\Ce;
use App\Model\GoodsModel;
use App\Model\GoodsLeiModel;
use App\Model\Test;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
class WuController extends Controller{
    public function index(){
        return view('ce.login');
    }
    public function indexadd(Request $request){
        if(empty($request->post('name'))||empty($request->post('pwd'))){
            echo '不能为空';die;
        }
        $name=$request->post('name');
        $pwd=$request->post('pwd');
//        var_dump($name);die;
        $obj=new \mysqli('192.168.1.120','root','root','ce');
        $sql = 'select * from (select * from ce_01 union select * from ce_02 union select * from ce_03) as u where u.name="'.$name . '"';
//        echo $sql;die;
        $res=$obj->query($sql)->fetch_array();
        var_dump($res);die;
    }
    //登录hash
    public function indexadda(Request $request){
        if(empty($request->post('name'))||empty($request->post('pwd'))){
            echo '不能为空';die;
        }
        $name=$request->post('name');
        $pwd=$request->post('pwd');
//        $name='苏洛';
//        $pwd='123';
        $hash=hash('md5',$name);
        $str=substr($hash,0,1);
        $str1=base_convert($str,16,10);//base_convert  在任意进制之间转换数字
        $table_name='ce_0'.$str1;
//        var_dump($name);die;
        $obj=new \mysqli('192.168.1.120','root','root','ce');
        $sql = 'select * from '.$table_name.' where name="'.$name . '" and pwd="'.$pwd.'"';
//        echo $sql;
        $res=$obj->query($sql)->fetch_array();
//        var_dump($res);die;
        if($res){
            return '登录成功';
        }else{
            return '失败';
        }
    }
    public function register(){
        return view('ce.register');
    }
    //注册hash
    public function cez(Request $request){
        if(empty($request->post('name'))||empty($request->post('pwd'))){
            echo '不能为空';die;
        }
        $name=$request->post('name');
        $pwd=$request->post('pwd');
//        $name='苏洛';
//        $pwd='123';
        $hash=hash('md5',$name);
        $str=substr($hash,0,1);
        $str1=base_convert($str,16,10);//base_convert  在任意进制之间转换数字
        $table_name='ce_0'.$str1;
        $obj=new \mysqli('192.168.1.120','root','root','ce');
        $sql = 'insert into '.$table_name.'(id,`name`,pwd) values(null,"'.$name.'",'.$pwd.')';
//        echo $sql;die;
        $res=$obj->query($sql);
        if($res){
            return '成功';
        }
    }
    //注册redis
    public function cerd(Request $request){
        if(empty($request->post('name'))||empty($request->post('pwd'))){
            echo '不能为空';die;
        }
        $name=$request->post('name');
        $pwd=$request->post('pwd');
//        $name='苏洛';
//        $pwd='123';
        $redis=new \Redis();
        $redis->open('127.0.0.1','6379');
        $key='myid_user_redis_:';
        $ad=$redis->incr($key); //incr自增
        $table_name='ce_0'.($ad%10);
        $obj=new \mysqli('192.168.1.120','root','root','ce');
        $sql = 'insert into '.$table_name.'(id,`name`,pwd) values(null,"'.$name.'",'.$pwd.')';
        echo $sql;
        $res=$obj->query($sql);
        var_dump($res);
        if($res){
            return '成功';
        }
    }
    //注册
    public function sandeng(){
        return view('ce.sandeng');
    }
    public function sandengadd(Request $request){
        if(empty($request->post('name'))||empty($request->post('email'))||empty($request->post('iPhone'))||empty($request->post('pwd'))){
            echo '不能为空';die;
        }
        $name=$request->post('name');
        $email=$request->post('email');
        $iPhone=$request->post('iPhone');
        $pwd=$request->post('pwd');
        //strlen  返回字符串的长度
        if(strlen($iPhone)==11){
//            $ciPhone=crc32($iPhone);
            $hash=hash('md5',$name);
            $str=substr($hash,0,1);
            $str1=base_convert($str,16,10);

            $where=['u_iphone'=>$iPhone];
            $sel_sql=DB::table('u_user')->where($where)->get()->toArray();
//                var_dump($sel_sql);die;
            if(!empty($sel_sql)){
                echo '一个手机号只能注册一次';die;
            }
            $where=['u_iphone'=>$iPhone,'u_name'=>$name,'u_email'=>$email,'u_id'=>$str1];
            $insert_sql=DB::table('u_user')->insert($where);
//                var_dump($insert_sql);die;
            if($insert_sql){
                $hash=hash('md5','12345');
                $str=substr($hash,0,1);
                $str1=base_convert($str,16,10);
                $table_name='ce_0'.$str1;
                $cpwd=crc32($pwd);
                $where=['name'=>$name,'pwd'=>$cpwd];
                $select_sql=DB::table($table_name)->insert($where);
                if($select_sql){
                    echo '成功';
                }
            }
        }
    }
    public function sansai(){
        return view('ce.sansai');
    }
    public function sansaiadd(Request $request){
        if(empty($request->post('name'))||empty($request->post('pwd'))){
            echo '不能为空';die;
        }
        $name=$request->post('name');
        $pwd=$request->post('pwd');
        if(is_numeric($name)){
            if(strlen($name)==11){
                $crname=crc32($name);
                $where=['u_iphone'=>$crname,'u_id'=>1];
                $insert_sql=DB::table('u_user')->where($where)->get();
//                var_dump($insert_sql);die;
                if($insert_sql){
                    $hash=hash('md5',$name);
                    $str=substr($hash,0,1);
                    $str1=base_convert($str,16,10);
                    $table_name='ce_0'.$str1;
                    $where=['name'=>$name,'pwd'=>$pwd];
                    $select_sql=DB::table($table_name)->where($where)->get();
                    if($select_sql){
                        echo '成功';
                    }
                }
            }
        }elseif(substr_count($name,'@')==1){
            $crname=crc32($name);
            $where=['u_email'=>$crname,'u_id'=>1];
            $insert_sql=DB::table('u_user')->where($where)->get();
            if($insert_sql){
                $hash=hash('md5',$name);
                $str=substr($hash,0,1);
                $str1=base_convert($str,16,10);
                $table_name='ce_0'.$str1;
                $where=['name'=>$name,'pwd'=>$pwd];
                $select_sql=DB::table($table_name)->where($where)->get();
                if($select_sql){
                    echo '成功';
                }
            }
        }else{
            $crname=crc32($name);
            $where=['u_name'=>$crname,'u_id'=>1];
            $insert_sql=DB::table('u_user')->where($where)->get();
            if($insert_sql){
                $hash=hash('md5',$name);
                $str=substr($hash,0,1);
                $str1=base_convert($str,16,10);
                $table_name='ce_0'.$str1;
                $where=['name'=>$name,'pwd'=>$pwd];
                $select_sql=DB::table($table_name)->where($where)->get();
                if($select_sql){
                    echo '成功';
                }
            }
        }
    }
    //静态页面
    public function shouye(){
        $goods_data=GoodsLeiModel::get()->toArray();
        return view('ce.shouye',['goods'=>$goods_data]);
    }
    //将页面静态化
    public function goods_data(Request $request){
        $goods_lei_id=$request->get('goods_lei_id');
//        $page='/home/wwwroot/default/cms/public/goods/product_detail_'.$goods_lei_id.'.html';
        $page='/data/wwwroot/default/1807larval/1807larval/public/goods/product_detail_'.$goods_lei_id.'.html';
        if(file_exists($page)){
            echo '静态页面';
            echo file_get_contents($page);die;
        }
        ob_start();
        $where=[
            'goods_lei_id'=>$goods_lei_id
        ];
        $goods_data=GoodsModel::where($where)->get()->toArray();
        $a=ob_get_contents();
        ob_end_flush();
//        file_put_contents('/home/wwwroot/default/cms/public/goods/product_detail_'.$goods_lei_id.'.html',$a);
        file_put_contents('/data/wwwroot/default/1807larval/1807larval/public/goods/product_detail_'.$goods_lei_id.'.html',$a);
        return view('ce.goods',['goods_data'=>$goods_data]);
    }
}
?>