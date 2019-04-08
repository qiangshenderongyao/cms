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
    public function ceshi(){
        $data=[
            'username'=>'赤耀',
            'age'=>'23'
        ];
        echo $data;
    }
}
?>