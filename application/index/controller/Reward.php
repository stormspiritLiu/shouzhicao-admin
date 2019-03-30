<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/30
 * Time: 14:44
 */

namespace app\index\controller;

use app\index\model\Reward as rewardModel;

class Reward extends Base
{
    public function index(){
        return $this->fetch();
    }

    public function rewardList(){
        $page = input('page') ? input('page') : 1;
        $limit = input('limit') ? input('limit') : 10;
        $key = input('key');
        $reward = new rewardModel();
        $data = $reward->pagingQuery($limit,$page,$key);
        return $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $data['data'], 'count' => $data['total']];
    }
}