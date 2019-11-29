<?php
declare (strict_types = 1);

namespace app\middleware;

use think\facade\Session;
use app\common\controller\Common;
class Login extends Common
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
       $is_login =  Session::get('admin_id');
//       return json($is_login);
       if (!$is_login){
           return $this->rtnInfo(1,'请重新登录');
       }

       return $next($request);
    }


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
