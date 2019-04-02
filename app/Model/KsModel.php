<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KsModel extends Model
{
    //
    public $table = 'shenq';
    public $timestamps = false;
    /**
     * 生成app_key和app_secret
     */
    public static function app_key()
    {
        return  rand(11111,99999) . rand(2222,9999);
    }
    public static function app_secret(){
        $str='0123456789abcdefghijklmnopqrstuvwxyz+-*/';
        $res=substr(str_shuffle($str),rand(1,20),6);
        return $res;
    }
}
