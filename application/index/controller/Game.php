<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/26
 * Time: 9:44
 */

namespace app\index\controller;

use app\index\model\Game as GameModel;
use think\Db;

class Game extends Base
{
    public function index(){
        return $this->fetch();
    }

    public function gameList(){
        $page = input('page') ? input('page') : 1;
        $limit = input('limit') ? input('limit') : 10;
        $key = input('key');
        $game = new GameModel();
        $data = $game->pagingQuery($limit,$page,$key);
        return $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $data['data'], 'count' => $data['total']];
    }

    public function delete(){
        $id = input('id');
        return Db::name('game')
            ->where('id', $id)
            ->useSoftDelete('delete_time',date("Y-m-d H:i:s" ,time()))
            ->delete();
    }

    public function instruction(){
        $id = input('id');
        $data = Db::name('game_instruction')
            ->where('gameId',$id)
            ->select();

        return $this->assign('list',$data)->fetch();
    }

    public function edit(){
        $id = input('id');
        if(request()->isPost()){
            $game = new GameModel();
            $game->save(input('post.'),['id'=>$id]);
            return json(['code' => 1, 'msg' => '编辑成功!']);
        }

        $game = Db::name('game')->where('id',$id)->find();
        $this->assign('game', $game);
        return $this->fetch();
    }
}