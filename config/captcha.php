<?php
/**
 * Created by PhpStorm.
 * User: LiuZhelin
 * Date: 2019/3/19
 * Time: 19:15
 */

// +----------------------------------------------------------------------
// | 验证码设置
// +----------------------------------------------------------------------

return [
    // 验证码字符集合
    'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
    // 验证码字体大小(px)
    'fontSize' => 28,
    // 是否画混淆曲线
    'useCurve' => false,
    // 验证码图片高度
    'imageH'   => 50,
    // 验证码图片宽度
    'imageW'   => 200,
    // 验证码位数
    'length'   => 4,
    //验证成功后是否重置
    'reset'    => true
];