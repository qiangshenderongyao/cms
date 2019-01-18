<?php
namespace App\Http\Controllers\Ce;
use App\Model\Test;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
/**
 * Created by PhpStorm.
 * User: 严世钰
 * Date: 2018/11/6
 * Time: 11:53
 */
class CeController extends Controller {
    public function ce(){
        $url='http://shop.96myshop.cn';
        $client=new Client(['base_uri'=>$url,'timeout'=>2.0,]);
        $response=$client->request('GET','/Order.php');
        echo $response->getBody();
    }
    public function cookieTest1()
    {
        setcookie('cookie1','lening',time()+1200,'/','lening.com',false,true);
        echo '<pre>';print_r($_COOKIE);echo '</pre>';
    }
}
?>