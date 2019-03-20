<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/20
 * Time: 16:15
 */

namespace app\index\model;

use think\Db;
use think\Model;
class User extends Model
{
    public function findAll(){
        return User::all();
    }
}