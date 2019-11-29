<?php

namespace app\admin;

use think\Model;
use think\facade\Session;

/**
 * Class ModelFunciton
 * @package app\admin\ModelFunciton
 * 这里常用方法进行封装
 */
class ModelFunciton extends Model
{
    public static function get($where)
    {
        if(!is_array($where)){
            return self::find($where);
        }else{
            return self::where($where)->find();
        }
    }

    /**
     * @param int $raw 默认 0为成功 1为失败
     * @param string $data 返回数据
     * @param string $message 返回信息
     * @return \think\response\Json 信息返回
     */
    public static function rtnInfo($raw = 0,$message = "处理成功",  $data = "")
    {

        $code = $raw == 0 ? 20000 : 60204;

        return   array(
                'code' => $code,
                'data' => $data,
                'message' => $message
        );

    }

}