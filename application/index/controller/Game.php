<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/26
 * Time: 9:44
 */

namespace app\index\controller;

use app\index\model\Game as GameModel;
use app\index\model\Music as MusicModel;
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
            $data = input('post.');
            Db::name('game')
                ->where('id', $id)
                ->update([
                    'name'           => $data['name'],
                    'level'          => $data['level'],
                    'experience'     => $data['experience'],
                    'difficulty'     => $data['difficulty'],
                    'price'          => $data['price'],
                ]);
            if( Db::name('game_music')->where('gameId',$id)->count() > 0){
                //更新游戏与音乐的关系
                Db::name('game_music')
                    ->where('gameId',$id)
                    ->update([
                        'musicId' => $data['music']
                    ]);
            }else{
                //新增关系
                Db::name('game_music')
                    ->insert([
                        'gameId'    => $id,
                        'musicId'   => $data['music']
                    ]);
            }

            return json(['code' => 1, 'msg' => '编辑成功!']);
        }

        $game = Db::name('game')->where('id',$id)->find();
        $musicList = Db::name('music')->where('delete_time',null)->select();
        $musicId = Db::name('game_music')->where('gameId',$id)->find();

        $this->assign([
            'game'=> $game,
            'musicList' => $musicList,
            'musicId' => $musicId
        ]);
        return $this->fetch();
    }

    public function newGame(){
        if(request()->isPost()){
            $data = input('post.');
            unset($data['music']);
            $gameId = Db::name('game')->insertGetId($data);
            if(!empty($gameId)){
                Db::name('game_music')->insert([
                    'gameId'    => $gameId,
                    'musicId'   => input('post.music')
                ]);
                return json(['code' => 1, 'msg' => '创建成功!']);
            }
        }

        $musicList = Db::name('music')->where('delete_time',null)->select();
        return $this->assign('musicList',$musicList)->fetch();
    }
}