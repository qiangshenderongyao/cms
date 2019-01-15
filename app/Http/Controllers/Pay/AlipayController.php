<?php

namespace App\Http\Controllers\Pay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\Model\OrderModel;
class AlipayController extends Controller
{
    //
    public $app_id = '2016091900550960';
    public $gate_way = 'https://openapi.alipaydev.com/gateway.do';
    public $return_url = 'http://cms2.com/pay/alipay/return/';
    public $notify_url = 'http://cms2.com/pay/alipay/notify/';
    // public $return_url = 'http://cms.96myshop.cn/pay/alipay/notify/';
    public $rsaPrivateKeyFilePath = './key/priv.key';

    //支付
    public function test($o_id)
    {
        //根据订单id查询金额
        $where=['o_id'=>$o_id];
        $price=OrderModel::where($where)->first();
        $order_price=$price['order_price']/100;
        // dump($order_price);die;
        $bizcont = [
            'subject'           => 'ancsd'. mt_rand(1111,9999).str_random(6),
            'out_trade_no'      => 'oid'.$o_id,
            'total_amount'      => $order_price,
            'product_code'      => 'QUICK_WAP_WAY',
        ];

        $data = [
            'app_id'   => $this->app_id,
            'method'   => 'alipay.trade.wap.pay',
            'format'   => 'JSON',
            'charset'   => 'utf-8',
            'sign_type'   => 'RSA2',
            'timestamp'   => date('Y-m-d H:i:s'),
            'version'   => '1.0',
            'notify_url'   => $this->notify_url,        //异步通知地址
            'return_url'   => $this->return_url,        // 同步通知地址
            'biz_content'   => json_encode($bizcont),
        ];
        // $data['o_id']=$o_id;
        // dump($data);die;
        $sign = $this->rsaSign($data);
        $data['sign'] = $sign;
        $param_str = '?';
        foreach($data as $k=>$v){
            $param_str .= $k.'='.urlencode($v) . '&';
        }
        $url = rtrim($param_str,'&');
        $url = $this->gate_way . $url;
        header("Location:".$url);
    }


    public function rsaSign($params) {
        return $this->sign($this->getSignContent($params));
    }

    protected function sign($data) {

        $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
        $res = openssl_get_privatekey($priKey);

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);

        if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }


    public function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

                // 转换成目标字符集
                $v = $this->characet($v, 'UTF-8');
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }

    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }


    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {

        if (!empty($data)) {
            $fileType = 'UTF-8';
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }

        return $data;
    }
    public function Return()
    {
        echo '<pre>';print_r($_GET);echo '</pre>';
        //验签 支付宝的公钥
        if(!$this->verify()){
            echo 'error';
        }

        //处理订单逻辑
        $this->dealOrder($_GET);
    }
    /**
     * 支付宝异步通知
     */
    public function notify()
    {

        $data = json_encode($_POST);
        $log_str = '>>>> '.date('Y-m-d H:i:s') . $data . "<<<<\n\n";
        //记录日志
        file_put_contents('logs/alipay.log',$log_str,FILE_APPEND);
        //验签
        $res = $this->verify();
        if($res === false){
            echo 'error';
            //记录日志 验签失败
        }

        //处理订单逻辑
        $this->dealOrder($_POST);

        echo 'success';
    }
    //验签
    function verify() {
        return true;
    }
    /**
     * 处理订单逻辑 更新订单 支付状态 更新订单支付金额 支付时间
     * @param $data
     */
    public function dealOrder($data)
    {
        echo '<pre>';print_r($_GET);echo '</pre>';
        $total_amount=$_GET['total_amount'];
        $out_id=$_GET['out_trade_no'];
        $o_id=substr($out_id,3);
        // dump($o_id);die;
        $o_where=['o_id'=>$o_id];
        $out_update=['pay_amount'=>$total_amount,'is_pay'=>2,'out_time'=>$_GET['timestamp']];
        // dump($out_update);die;
        $out_data=OrderModel::where($o_where)->update($out_update);
        if($out_data){
            echo '订单支付成功';
            return redirect('/center');
        }
    }
}
