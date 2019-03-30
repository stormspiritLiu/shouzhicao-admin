<?php

namespace app\index\model;

use think\Model;
class Recharge extends Model
{
    public function pagingQuery($list_rows,$page,$key){
        $result = Recharge::order('r.id')
            ->alias('r')
            ->leftJoin('user u','r.userId = u.id')
            ->where('u.name|r.id', 'like', "%" . $key . "%")
            ->field('r.id,u.name as userName,r.amount, r.time')
            ->paginate(array('list_rows' => $list_rows, 'page' => $page))
            ->toArray();
        return $result;
    }
}