<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Test extends Model{
    const UPDATE_AT=NULL;
    protected  $table='ceshi';
    public  function  getAlltest(){
        return $this->paginate($perPage=2,$columns=['*'],$pageName='page',$page=null);
    }
}
?>