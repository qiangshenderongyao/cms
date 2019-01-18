<?php
namespace App\Http\Controllers\Good;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use App\Model\CartModel;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Resource_;

class GoodsController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }
    //商品详情页
    public function goodsadd($goods_id){
        $where=['goods_id'=>$goods_id];
        // dump($where);die;
        $data=GoodsModel::where($where)->first();
        if(!$data){
            return redirect('/');
            echo '商品不存在';exit;
        }
        // dump($data);die;
        return view('Goods.goodsadd',['data'=>$data]);
    }
    //商品列表
    public function goods(){
        $data=GoodsModel::all();
        if(!$data){
            return redirect('/');
            echo '商品不存在';exit;
        }
        return view('Goods.goods',['data'=>$data]);
    }
}