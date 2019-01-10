<?php
namespace App\Http\Controllers\Cart;
use App\Model\GoodsModel;
use App\Model\CartModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
class IndexController extends Controller{
    //购物车
    public function index(){
//        $goods=session()->get('cart_goods');//获取session中的字段值
//        if(empty($goods)){
//            echo '购物车是空的';
//        }else{
//            //若不为空,循环输出id值和查询表里对应的值
//            foreach($goods as $k=>$v){
//                echo 'Goods ID: '.$v;echo '</br>';
//                $detail = GoodsModel::where(['goods_id'=>$v])->first()->toArray();
//                echo '<pre>';print_r($detail);echo '</pre>';
//                return redirect('/request');//直接跳到列表页
//            }
//        }
        $uid = session()->get('uid');//获取uid
//        dump($uid);die;
        $cart_goods = DB::table('shop_cart')->where(['uid'=>$uid])->get()->toArray();
        // dump($cart_goods);die;
        if(empty($cart_goods)){
            echo '购物车是空的';
            return redirect('/goods');exit;
        }
        if($cart_goods){
            //获取商品最新信息
            foreach($cart_goods as $k=>$v){
                $where=['goods_id'=>[$v->goods_id]];
                $goods_data = GoodsModel::where($where)->first();
                $goods_info=json_decode($goods_data,true);
                $goods_info['num']  = $v->num;
                $goods_info['uid']=$v->uid;
                // echo '<pre>';print_r($goods_info);echo '</pre>';die;
                $list[] = $goods_info;
            }
        }

        $data = [
            'list'  => $list
        ];
        return view('cart.index',$data);
    }
    public function cart(){
        $data = GoodsModel::get();
//        dump($data);die;
        return view('Cart.cart',['data'=>$data]);
    }
    //添加
    public function add($goods_id){
        $cart_goods=session()->get('cart_goods');//获取session中的字段值
        //是否存入购物车
        if(!empty($cart_goods)){
            //in_array() 函数搜索数组中是否存在指定的值。
            //判断id和值是否存在
            if(in_array($goods_id,$cart_goods)) {
                echo '已存入购物车';
                exit;
            }
        }
        //push() 方法可向数组的末尾添加一个或多个元素，并返回新的长度。
        session()->push('cart_goods',$goods_id);
        //减少库存量
        $where=['goods_id'=>$goods_id];
        //value函数:将代表数字的文本字符串转换成数字
        $store=GoodsModel::where($where)->value('store');
        if($store<=0){
            echo '库存不足';exit;
        }
        //decrement:消耗
        $rs = GoodsModel::where(['goods_id'=>$goods_id])->decrement('store');

        if($rs){
            echo '添加成功';
        }
    }
    /*返回用户id*/
    public function __construct(){
        $this->middleware(function ($request, $next) {
           $this->data = request()->session()->get('uid');
           return $next($request);
        });
    }
    //添加商品
    public function add2(Request $request)
    {
        $goods_id = $request->input('goods_id');//接收值
        $num = $request->input('num');
        // dump($goods_id);die;
        //检查库存
        $store_num = GoodsModel::where(['goods_id'=>$goods_id])->value('store');
        if($store_num<=0){
            $response = [
                'errno' => 5001,
                'msg'   => '库存不足'
            ];
            return $response;
        }
        //检查购物车重复商品
        $cart_goods = CartModel::where(['uid'=>$this->data])->get()->toArray();
        if($cart_goods){
            //array_column() 返回输入数组中某个单一列的值。
            $goods_id_arr = array_column($cart_goods,'goods_id');
            // in_array() 函数搜索数组中是否存在指定的值。
            if(in_array($goods_id,$goods_id_arr)){
                $response = [
                    'errno' => 5002,
                    'msg'   => '商品已在购物车中，请勿重复添加'
                ];
                return $response;
            }
        }
        //写入购物车表
        $data = [
            'goods_id'  => $goods_id,
            'num'       => $num,
            'add_time'  => time(),
            'uid'       => session()->get('uid'),
            'session_token' => session()->get('u_token')
        ];

        $cid = CartModel::insertGetId($data);
        if(!$cid){
            $response = [
                'errno' => 5002,
                'msg'   => '添加购物车失败，请重试'
            ];
            return $response;
        }


        $response = [
            'error' => 0,
            'msg'   => '添加成功'
        ];
        return $response;
        return redirect('/cart');
    }
    //删除
    public function delete($goods_id){
        //判断 商品是否在 购物车中
        $goods = session()->get('cart_goods');
        echo '<pre>';print_r($goods);echo '</pre>';die;
        //判断id和商品是否存在
        if(in_array($goods_id,$goods)){
            //执行删除
            foreach($goods as $k=>$v){
                if($goods_id == $v){
                    //将session中字段值pull下来
                    session()->pull('cart_goods.'.$k);
                }
            }
        }else{
            //不在购物车中
            die("商品不在购物车中");
        }
    }
    public function del($goods_id){
        $where=['uid'=>$this->data,'goods_id'=>$goods_id];
        // dump($where);die;
        $data = DB::table('shop_cart')->where($where)->delete();
        // dump($data);die;
        //echo '商品ID:  '.$goods_id . ' 删除成功1';
        if($data){
            echo '商品ID:  '.$goods_id . ' 删除成功1';
            return redirect('/cart');
        }else{
            echo '商品ID:  '.$goods_id . ' 删除成功2';
            return redirect('/cart');
        }
    }
}
?>