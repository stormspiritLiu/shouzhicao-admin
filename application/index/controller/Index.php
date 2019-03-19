<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Base
{
    public function initialize(){
        parent::initialize();
    }

    public function index()
    {
        return $this->fetch();
    }
}
