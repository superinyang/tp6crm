<?php

namespace app\admin\model;

//模块统一继承方法
use app\admin\ModelFunciton;
use think\facade\Session;

/**
 * Class SystemAdmin
 * @package app\admin\model/SystemAdmin
 */
class SystemAdmin extends ModelFunciton
{
    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'system_user';


    protected $insert = ['add_time'];

    public static function setAddTimeAttr($value)
    {
        return time();
    }
    //权限管理器
    public static function setRoles($value)
    {
        //先仍这里回头写
    }

    /**
     * 用户登陆
     * @param $account
     * @param $pwd
     * @return bool
     */
    public static function login($account,$pwd,$token)
    {
        $adminInfo = self::get(compact('account'));

        if(!$adminInfo) return self::rtnInfo(1,'登陆的账号不存在!');

        if($adminInfo['pwd'] != md5($pwd)) return self::rtnInfo(1,'账号或密码错误，请重新输入');

        if(!$adminInfo['status']) return self::rtnInfo('该账号已被关闭!');

        self::setLoginInfo($adminInfo);

//        event('SystemAdminLoginAfter',[$adminInfo]);//监听方法 算了 先不写了 代码量太大回头写

        Session::set('token',$token);

        return  self::rtnInfo(0,'登录成功',array('token'=>$token));
    }

    /**
     *  保存当前登陆用户信息到缓存
     */
    public static function setLoginInfo($adminInfo)
    {

        Session::set('admin_id',$adminInfo['id']);

        Session::set('adminInfo',$adminInfo->toArray());
    }

    /**
     *  获取用户列表
     */
    public static function getList($page,$size,$sort,$order,$key='')
    {
        if (!empty($key)){
            $like = $key.'%';
            $data['list'] = SystemAdmin::where('name|nickName','like',$like)->page($page,$size)->order( $order ,$sort)->select();
            $data['pagination']['count']= SystemAdmin::where('name|nickName|phone|username|','like',$like)->count();
            $data['pagination']['total'] = ceil($data['pagination']['count']/$size);
            $data['pagination']['size'] = $size;
            $data['pagination']['page'] = $page;
        }else{
            $data['list'] = SystemAdmin::page($page,$size)->order( $order,$sort)->select();
            $data['pagination']['count'] = SystemAdmin::count();
            $data['pagination']['total'] = ceil($data['pagination']['count']/$size);
            $data['pagination']['size'] = $size;
            $data['pagination']['page'] = $page;
        }
        return $data;
    }
    /**
     * 清空当前登陆用户信息
     */
    public static function clearLoginInfo()
    {
        Session::delete('adminInfo');
        Session::delete('adminId');
        Session::clear();
    }

    /**
     * 检查用户登陆状态
     * @return bool
     */
    public static function hasActiveAdmin()
    {
        return Session::has('adminId') && Session::has('adminInfo');
    }

    /**
     * 获得登陆用户信息
     * @return mixed
     * @throws \Exception
     */
    public static function activeAdminInfoOrFail()
    {
        $adminInfo = Session::get('adminInfo');
        if(!$adminInfo)  exception('请登陆');
        if(!$adminInfo['status']) exception('该账号已被关闭!');
        return $adminInfo;
    }

    /**
     * @param $id 通过id 查询用户数据信息
     */
    public static function getUserInfo($id){
        $data = SystemAdmin::find($id);
        return $data;
    }
    /**
     * 获得登陆用户Id 如果没有直接抛出错误
     * @return mixed
     * @throws \Exception
     */
    public static function activeAdminIdOrFail()
    {
        $adminId = Session::get('adminId');
        if(!$adminId) exception('访问用户为登陆登陆!');
        return $adminId;
    }
    public static function getUserUpdate($update){

        $data = SystemAdmin::update($update);

    }

    public static function getUserDelete($id){

        if ($id==10000){
            return 1001;
        }
        $array = array(
            'id'=>$id,
            'is_del'=>1
        );
        $data = SystemAdmin::update($array);

        return 1000;

    }
    /**
     * @return array|null
     * @throws \Exception
     */
    public static function activeAdminAuthOrFail()
    {
        $adminInfo = self::activeAdminInfoOrFail();
        if(is_object($adminInfo)) $adminInfo = $adminInfo->toArray();
        return $adminInfo['level'] === 0 ? SystemRole::getAllAuth() : SystemRole::rolesByAuth($adminInfo['roles']);
    }

    /**
     * 获得有效管理员信息
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function getValidAdminInfoOrFail($id)
    {
        $adminInfo = self::get($id);
        if(!$adminInfo) exception('用户不能存在!');
        if(!$adminInfo['status']) exception('该账号已被关闭!');
        return $adminInfo;
    }

    /**
     * @param string $field
     * @param int $level
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getOrdAdmin($field = 'real_name,id',$level = 0){
        return self::where('level','>=',$level)->field($field)->select();
    }

    public static function getTopAdmin($field = 'real_name,id')
    {
        return self::where('level',0)->field($field)->select();
    }

    /**
     * @param $where
     * @return array
     */
    public static function systemPage($where){
        $model = new self;
        if($where['name'] != '') $model = $model->where('account|real_name','LIKE',"%$where[name]%");
        if($where['roles'] != '') $model = $model->where("CONCAT(',',roles,',')  LIKE '%,$where[roles],%'");
        $model = $model->where('level',$where['level'])->where('is_del',0);
        return self::page($model,function($admin){
            $admin->roles = SystemRole::where('id','IN',$admin->roles)->column('role_name','id');
        },$where);
    }
}