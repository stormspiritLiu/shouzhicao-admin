<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/19
 * Time: 19:38
 */

namespace app\index\model;
use think\Db;
use think\Model;
class Admin extends Model
{
    public function login($data){
        $user=Db::name('admin')->where('name',$data['name'])->find();
        if($user){
            if($user['password'] == md5($data['password'])){
                session('name',$user['name']);
                session('admin',$user['id']);
                return 1; //信息正确
            }else{
                return -1; //密码错误
            }
        }else{
            return -1; //用户不存在
        }
    }
}