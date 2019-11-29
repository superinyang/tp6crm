<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\common\controller\Admin;
use think\facade\Session;

class User extends Admin
{
    public function index()
    {
        return '您好！这是一个[admin]示例应用';
    }

    public function info()
    {
        $adminInfo = Session::get('adminInfo');
        $admin = array('roles' => ['admin'],
            'introduction' => 'I am a super administrator',
            'avatar' => $adminInfo['headImg'],
            'name' => $adminInfo['name']);
        return $this->rtnInfo(0, '', $admin);
    }
}
