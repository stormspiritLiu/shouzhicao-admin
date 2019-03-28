<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/19
 * Time: 14:27
 */

namespace app\index\controller;

use app\index\model\Admin;
use think\Controller;
use think\facade\Session;

class Login extends Controller
{
    public function index()
    {
        if(request()->isPost()){
            $admin = new Admin();
            $data = input('post.');
            $num = $admin->login($data);
            if(!$this->check($data['captcha'])){
                return json(array('code' => 0, 'msg' => '验证码错误'));
            }
            if($num == 1){
                return json(['code' => 1, 'msg' => '登录成功!', 'url' => url('index/index')]);
            }else {
                return json(array('code' => 0, 'msg' => '用户名或者密码错误，重新输入!'));
            }
        }
        return $this->fetch();
    }

    public function check($code){
        return captcha_check($code);
    }

    public function logout(){
        if(Session::has('admin')){
            Session::delete('admin');
        }
        return $this->fetch('index');
    }
}