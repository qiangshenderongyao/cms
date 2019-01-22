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