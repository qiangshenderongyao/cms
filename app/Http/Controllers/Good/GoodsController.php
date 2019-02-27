<?php
namespace App\Http\Controllers\Good;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use App\Model\CartModel;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Resource_;
use Illuminate\Support\Facades\Redis;
use Predis\Client;
class GoodsController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }
    //商品详情页
    public function goodsadd($goods_id){
//        $redis_goods_key='h_goods_info_'.$goods_id;
//        $goods_info=Redis::hGetAll($redis_goods_key);
//        if($goods_info){
//            echo 'Redis';echo '<br>';
//            echo '<pre>';print_r($goods_info);echo '</pre>';
//        }else{
//            echo 'Mysql';echo '</br>';
//            $goods_info=GoodsModel::where(['goods_id'=>$goods_id])->first()->toArray();
//            echo '<pre>';print_r($goods_info);echo '</pre>';
//            //写入缓存
//            $redis=Redis::hmset($redis_goods_key,$goods_info);
//            //设置缓存过期时间
//            Redis::expire($redis_goods_key,30);
//        }
//        die;
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
        $data=GoodsModel::paginate(2);
        if(!$data){
            return redirect('/');
            echo '商品不存在';exit;
        }
        return view('Goods.goods',['data'=>$data]);
    }
    //更新商品信息
    public function updateGoodsInfo($goos_id){
        $name=str_random(10);
        $info=['goods_name'=>$name,'add_time'=>time(),'price'=>mt_rand(111,999)];
        echo '<pre>';print_r($info);echo '</pre>';
        //1、更新数据库
        GoodsModel::where(['goods_id'=>$goos_id])->update($info);
        //2、更新缓存
        $redis_goods_key='h_goods_info_'.$goos_id;
        echo $redis_goods_key;
        Redis::hMset($redis_goods_key,$info);
    }
    //Redis做座位订阅
    public function Rediszuo(){
        $Redis_key='test_bit';
        $Redis_data=[];
        for($i=0;$i<=30;$i++){
            $status = Redis::getBit($Redis_key,$i);   //判断当前位 为0 或者 为1
            $seat_status[$i] = $status;
        }
        $data=['data'=>$seat_status];
        return view('Goods.Rediszuo',$data);
    }
    /**
     * @param $pos  座位号
     * @param $status   0 | 1
     */
    //Redis订座购买
    public function Redisbuy($pos,$status){
        $Redis_key='test_bit';
        Redis::setbit($Redis_key,$pos,$status);
    }
    //商品搜索
    public function goodsou()
    {
        $sou = request()->post('so');
        $where = ['goods_name' => $sou];
        if (empty($sou)) {
            $data = GoodsModel::paginate(2);
        } else {
            $data = GoodsModel::where($where)->paginate(2);
        }
//        dump($data);die;
        return view('Goods.goodsou', ['data' => $data, 'sou' => $sou]);
    }
    public function upload(){
        return view('Goods.upload');
    }
    public function uploadpdf(Request $request){
        echo '</pre>';print_r($_FILES);echo '</pre>';
        $pdf=$request->file('filename');
        print_r($pdf);
        $ext=$pdf->extension();
        if($ext!='pdf'){
            die("请上传PDF格式");
        }
        $res=$pdf->storeAs(date('Ymd'),str_random(5).'.pdf');
        if($res){
            echo '上传成功';
            return redirect('/upload');
        }
    }
}