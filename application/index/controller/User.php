<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/20
 * Time: 12:49
 */

namespace app\index\controller;

use app\index\model\User as UserModel;
use think\Db;
class User extends Base
{
    public function index(){
        return $this->fetch();
    }

    public function userList(){
        $page = input('page') ? input('page') : 1;
        $limit = input('limit') ? input('limit') : 10;
        $user = new UserModel();
        $data = $user->pagingQuery($limit,$page);
        return $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $data['data'], 'count' => $data['total']];
    }

    public function lock(){
        $data = input('post.');
        $user = new UserModel();
        return $user->ban($data['id']);
    }

    public function delete(){
        $id = input('id');
        return Db::name('user')
            ->where('id', $id)
            ->useSoftDelete('delete_time',date("Y-m-d H:i:s" ,time()))
            ->delete();
    }
}