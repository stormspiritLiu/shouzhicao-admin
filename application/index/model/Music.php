<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/28
 * Time: 17:00
 */

namespace app\index\model;

use think\Model;
class Music extends Model
{
    public function pagingQuery($list_rows,$page,$key){
        return Music::order('id')
            ->where('name|id', 'like', "%" . $key . "%")
            ->paginate(array('list_rows' => $list_rows, 'page' => $page))
            ->toArray();
    }
}