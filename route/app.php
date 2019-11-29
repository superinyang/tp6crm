<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::any('admin/:name/:action', 'admin/:name/:action')
    ->header('Access-Control-Allow-Origin','https://www.fxd1.com.cn')
    ->header('Access-Control-Allow-Credentials', 'true')
//    ->header('Access-Control-Max-Age: 1728000')
    ->allowCrossDomain();

