<?php
namespace App\Http\Controllers\Order;
use App\Model\GoodsModel;
use App\Model\CartModel;
use App\Model\OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
class OrderController extends Controller{
	/*返回用户id*/
    public function __construct(){
        $this->middleware(function ($request, $next) {
           $this->data = request()->session()->get('uid');
           return $next($request);
        });
    }
	public function order(){
		//查询购物车表中数据
		$where=['uid'=>$this->data];
		$data=CartModel::where($where)->get()->toArray();
		if(empty($data)){
			echo '购物车数据为空';die;
		}
		//总金额
		$order_price=0;
		foreach($data as $k=>$v){
			//查询商品表
			$where=['goods_id'=>$v['goods_id']];
			$goods_info = GoodsModel::where($where)->first()->toArray();
            $goods_info['num'] = $v['num'];
            $list[] = $goods_info;
            //订单总金额
            $order_price += $goods_info['price']*$v['num'];
		}
		//生成订单号
        $order_name = OrderModel::generateOrderSN();
        echo $order_name;
        $data = [
            'order_name'      => $order_name,
            'uid'           => $this->data,
            'add_time'      => time(),
            'order_price'  => $order_price
        ];
        //入库后返回订单号id
        $order_data = OrderModel::insertGetId($data);
        if(!$order_data){
            echo '生成订单失败';
        }

        echo '下单成功,订单号：'.$order_data .' 跳转支付';

        //清空购物车
        CartModel::where(['uid'=>$this->data])->delete();
        return redirect('/orderlist');
	}
    public function orderlist(){
        $data=OrderModel::where('is_pay',0)->get()->toArray();
        if(!$data){
            return redirect('/');
            echo '商品不存在';exit;
        }
        // if($data['is_pay']==0){
        //     $ze='未支付';
        //     return view('Order.orderlist',['data'=>$data,'ze'=>$ze]);
        // }elseif($data['is_pay']==1){
        //     $ze='已支付';
        //     return view('Order.orderlist',['data'=>$data,'ze'=>$ze]);
        // }
        return view('Order.orderlist',['data'=>$data]);
    }
    //支付
    public function orderzhi($o_id){
        //查询订单
        $where=['o_id'=>$o_id];
        $order_info=OrderModel::where($where)->first();
        if(empty($order_info)){
            die('订单'.$o_id.'不存在');
        }
        // dump($order_info);die;
        //检查订单状态
        if($order_info->pay_time>0){
            die('订单已被支付');
        }
        print_r($order_info);
        //支付成功 修改支付时间
        //pay_amount:支付金额
        OrderModel::where(['o_id'=>$o_id])->update(['pay_time'=>time(),'pay_amount'=>rand(1111,9999),'is_pay'=>1]);
        //积分  
        $integral=0;
        $data=OrderModel::where(['o_id'=>$o_id])->first();
        $integral=$data['pay_amount']/100;
        $where=['uid'=>$this->data];
        $data=DB::table('ceshi')->where($where)->update(['integral'=>$integral]);
        // dump($data);die;
        return redirect('/centeradd');
        echo '支付成功，正在跳转';
    }
}