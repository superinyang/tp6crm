<?php
declare (strict_types=1);

namespace app\common\controller;

use app\BaseController;

class Common extends BaseController
{
    public function index()
    {
        return '1';
    }

    /**
     * @param int $raw 默认 0为成功 1为失败
     * @param string $data 返回数据
     * @param string $message 返回信息
     * @return \think\response\Json 信息返回
     */
    public function rtnInfo($raw = 0,$message = "处理成功",  $data = "")
    {

        $code = $raw == 0 ? 20000 : 60204;

        return json(

            array(
                'code' => $code,
                'data' => $data,
                'message' => $message
            )

        );

    }
}
