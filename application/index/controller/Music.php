<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/28
 * Time: 16:43
 */

namespace app\index\controller;

use app\index\model\Music as MusicModel;
use think\Db;

class Music extends Base
{
    public function index(){
        return $this->fetch();
    }

    public function musicList(){
        $page = input('page') ? input('page') : 1;
        $limit = input('limit') ? input('limit') : 10;
        $key = input('key');
        $music = new MusicModel();
        $data = $music->pagingQuery($limit,$page,$key);
        return $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $data['data'], 'count' => $data['total']];
    }


    public function upload(){
        $file = request()->file('file');
        if(!empty($file)){
            // 移动到框架应用根目录/public/music 目录下
            $info = $file->move( '../public/music');
            if($info){
                $musicPath =  $info->getSaveName();
                $original = $info->getInfo();

                $getID3 = new \getID3();
                $ThisFileInfo = $getID3->analyze($_SERVER['DOCUMENT_ROOT'].'/music/'.$musicPath);
                $fileDuration= intval($ThisFileInfo['playtime_seconds']);

                Db::name('music')->insert([
                    'name'  => $original['name'],
                    'path'  => $musicPath,
                    'time'  => $fileDuration
                ]);

                return json(['code' => 1, 'msg' => '上传成功']);
            }else{
                // 上传失败获取错误信息
                return json(['code' => 0, 'msg' => $file->getError()]);
            }
        }else{
            // 上传失败获取错误信息
            return json(['code' => 0, 'msg' => '文件为空']);
        }
    }

    public function delete(){
        $id = input('id');
        return Db::name('music')
            ->where('id', $id)
            ->useSoftDelete('delete_time',date("Y-m-d H:i:s" ,time()))
            ->delete();
    }
}