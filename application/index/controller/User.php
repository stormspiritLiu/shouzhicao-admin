<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/20
 * Time: 12:49
 */

namespace app\index\controller;

use app\index\model\User as UserModel;
class User extends Base
{
    public function index(){
        return $this->fetch();
    }

    public function userList(){
        $user = new UserModel();
        return $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $user->findAll()];
    }
}