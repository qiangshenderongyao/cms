<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    //
	public $table = 'ceshi';
	public $timestamps = false;
	protected $primaryKey = 'uid';
}