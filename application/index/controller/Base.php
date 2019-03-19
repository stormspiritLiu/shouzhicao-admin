<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/19
 * Time: 19:32
 */

namespace app\index\controller;

use think\Controller;
class Base extends Controller
{
    public function initialize(){
        if (!session('admin')) {
            $this->redirect('login/index');
        }
    }
}