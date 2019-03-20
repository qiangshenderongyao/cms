<?php
namespace App\Http\Controllers\test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class TestController extends Controller{
    public function test1(){
        $data=[
            'name'=>'枪神',
            'age'=>20
        ];
       echo json_encode($data);
    }
}
?>