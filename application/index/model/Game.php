<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/26
 * Time: 10:06
 */

namespace app\index\model;

use think\Model;
class Game extends Model
{
    public function pagingQuery($list_rows,$page,$key){
        return Game::order('id')
            ->where('delete_time',null)
            ->where('name|id', 'like', "%" . $key . "%")
            ->paginate(array('list_rows' => $list_rows, 'page' => $page))
            ->toArray();
    }
}