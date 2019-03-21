<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/20
 * Time: 16:15
 */

namespace app\index\model;

use think\Model;
class User extends Model
{
    public function pagingQuery($list_rows,$page){
        return User::order('id')
            ->paginate(array('list_rows' => $list_rows, 'page' => $page))
            ->toArray();
    }
}