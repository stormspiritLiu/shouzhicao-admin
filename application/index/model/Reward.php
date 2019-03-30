<?php

namespace app\index\model;

use think\Model;
class Reward extends Model
{
    public function pagingQuery($list_rows,$page,$key){
        $result = Reward::order('r.id')
            ->alias('r')
            ->leftJoin('user u','r.userId = u.id')
            ->where('u.name|r.id', 'like', "%" . $key . "%")
            ->field('r.id,u.name as userName,r.award,r.experience,r.source, r.time')
            ->paginate(array('list_rows' => $list_rows, 'page' => $page))
            ->toArray();
        foreach ($result['data'] as $key => $value){
            if($value['source'] == 1){
                $result['data'][$key]['source'] = '游戏';
            }else {
                $result['data'][$key]['source'] = '任务';
            }
        }
        return $result;
    }
}