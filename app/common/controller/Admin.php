<?php
declare (strict_types = 1);

namespace app\common\controller;

use app\common\controller\Common;
use app\middleware\Login;
use think\facade\Session;

class Admin extends Common
{
    /**
     * @var array 中间件加入 集成Admin 限制登录
     */

   protected $middleware = [Login::class];

    public function index()
    {

    }
}
