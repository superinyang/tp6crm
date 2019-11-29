<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\Session;
class Index
{
    public function index()
    {
        Session::set('a',1);
        dump(Session::get('a'));
        return '您好！这是一个[admin]示例应用';
    }
    public function bndex(){
        dump(Session::get('a'));
    }
}
