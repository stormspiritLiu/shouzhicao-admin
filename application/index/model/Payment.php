<?php

namespace app\index\model;

use think\Model;
class Payment extends Model
{
    public function pagingQuery($list_rows,$page,$key){
        $result = Payment::order('p.id')
            ->alias('p')
            ->leftJoin('user u','p.userId = u.id')
            ->leftJoin('game g','p.gameId = g.id')
            ->where('u.name|g.name|p.id', 'like', "%" . $key . "%")
            ->field('p.id,u.name as userName,g.name as gameName, g.price,p.type, p.time')
            ->paginate(array('list_rows' => $list_rows, 'page' => $page))
            ->toArray();
        foreach ($result['data'] as $key => $value){
            if($value['type'] == 1){
                $result['data'][$key]['type'] = '健康豆';
            }else {
                $result['data'][$key]['type'] = '钻石';
            }
        }
        return $result;
    }
}