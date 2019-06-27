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
        return Game::order('g.id')
            ->alias('g')
            ->leftJoin('game_music gm','g.id = gm.gameId')
            ->leftJoin('music m','gm.musicId = m.id')
            ->where('g.delete_time',null)
            ->where('g.name|g.id', 'like', "%" . $key . "%")
            ->field('g.id,g.name,g.level,g.small_level,g.award,g.experience,g.difficulty,g.price,m.name as music,m.path as musicPath')
            ->paginate(array('list_rows' => $list_rows, 'page' => $page))
            ->toArray();
    }
}