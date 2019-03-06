<?php
namespace App\Http\Controllers\websocket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class WsController extends  Controller{
    public function ws(){
        return view('websocket.ws');
    }
}
?>