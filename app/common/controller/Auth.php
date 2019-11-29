<?php
declare (strict_types=1);

namespace app\common\controller;

use tauthz\facade\Enforcer;

class Auth
{
    /**
     * @param string $uid
     * @return mixed 获取用户的所有角色
     */
    public function getUserAuth($uid='')
    {
       $data = Enforcer::getRolesForUser($uid);

       return $data;
    }
    /**
     * @param string $uid
     * @return mixed 给予用户某个角色
     * true 为成功 false 为操作失败
     */
    public function setUserAuth($uid='',$role)
    {
        $data = Enforcer::addRoleForUser($uid, $role);

        return $data;
    }
}
