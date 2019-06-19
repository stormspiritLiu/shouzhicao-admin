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
use PHPExcel_IOFactory;

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
            ->order('id')
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

    public function upload(){
        $input = input('post.');
        $file = request()->file('file');
        // 移动到框架应用根目录/public/instruction/ 目录下
        if(!empty($file)){
            $info = $file->move('../public/instruction');
            if($info){
                $saveName =  $info->getSaveName();
                $path = $_SERVER['DOCUMENT_ROOT'].'/instruction/'.$saveName;
//                $file = fopen($path, 'r');
//                while(!feof($file)) {
//                    $row = fgets($file);
//                    $row =  str_replace(PHP_EOL, '', $row);
//                    if($row){
//                        $insert_data = ['gameId' => $data['id'], 'instruction' => $row];
//                        Db::name('game_instruction')->insert($insert_data);
//                    }
//                }
                $suffix = $info->getExtension();
                //判断哪种类型
                if($suffix=="xlsx"){
                    $reader = PHPExcel_IOFactory::createReader('Excel2007');
                }else{
                    $reader = PHPExcel_IOFactory::createReader('Excel5');
                }
                //载入excel文件
                $excel = $reader->load($path,$encode = 'utf-8');
                //读取第一张表
                $sheet = $excel->getSheet(0);
                //获取总行数
                $row_num = $sheet->getHighestRow();
                //获取总列数
                $col_num = $sheet->getHighestColumn();

                $data = [];

                for($i = 1; $i <= $row_num; $i++) {
                    $data[$i] = $sheet->getCell("A".$i)->getValue();
                    $length = strlen($data[$i]);
                    if($length != 20){
                        return json(['code' => 0, 'msg' => '第'.$i.'行指令长度不等于20']);
                    }
                    $head = substr($data[$i],0,4);
                    if($head != 'aa07'){
                        return json(['code' => 0, 'msg' => '第'.$i.'行指令起始不等于aa07']);
                    }
                    $lightId = substr($data[$i],4,2);
                    if($lightId < '00' || $lightId > '09'){
                        return json(['code' => 0, 'msg' => '第'.$i.'行指令灯ID不在00~09中']);
                    }
                    $startTime = substr($data[$i],6,6);
                    if($startTime < '000000' || $startTime > '595999'){
                        return json(['code' => 0, 'msg' => '第'.$i.'行指令起始时间不正确']);
                    }
                    $endTime = substr($data[$i],12,6);
                    if($endTime < '000000' || $endTime > '595999'){
                        return json(['code' => 0, 'msg' => '第'.$i.'行指令结束时间不正确']);
                    }
                    $end = substr($data[$i],18,2);
                    if($end != 'ff'){
                        return json(['code' => 0, 'msg' => '第'.$i.'行指令结束不等于ff']);
                    }
                }

                for($i = 1; $i <= $row_num; $i++) {
                    $insert_data = ['gameId' => $input['id'], 'instruction' => $data[$i]];
                    Db::name('game_instruction')->insert($insert_data);
                }

                return json(['code' => 1, 'msg' => '上传成功']);
            }
        }
        return json(['code' => 0, 'msg' => '文件不存在!']);
    }
}