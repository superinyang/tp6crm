<?php
declare (strict_types = 1);

namespace app\admin\controller;


use app\common\controller\Common;
use app\plus\controller\Captcha;
use think\facade\Session;
use think\Request;
use app\admin\model\SystemAdmin;

class Login extends Common
{
    public function Captcha()
    {
        $ver = new Captcha();

        $ver->width = 300;

        $ver->height = 100;

        $ver->nums = 4;

        $ver->random = '1234567890';

        $ver->font_size = 40;

        $code = $ver->getCode();

        $ver->doImg($code);
    }

    public function login(Request $request){

        //请求必须来自 Post 否则 返回错误信息
        if (!request()->isPost()) return $this->rtnInfo(1,'非法请求');

        //登陆次数记录
        $error = Session::get('login_error') ?: ['num' => 0, 'time' => time()];

        $error['num'] = 0;

        if ($error['num'] >= 5 && $error['time'] > strtotime('- 5 minutes')) return  $this->rtnInfo(1,'错误次数过多,请稍候再试!','');

        $startLogin  =  SystemAdmin::login(request()->param('username'), request()->param('password'), request()->buildToken('__token__', 'sha1'));;

        return json($startLogin);

    }
}
