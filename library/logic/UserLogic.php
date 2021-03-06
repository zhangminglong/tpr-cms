<?php
/**
 * @author: axios
 *
 * @email: axiosleo@foxmail.com
 * @blog:  http://hanxv.cn
 * @datetime: 2017/12/25 16:04
 */

namespace library\logic;

use think\Db;
use think\Session;

class UserLogic
{
    public static $user = null;

    /**
     * @param $open_id
     * @param $wechat
     * @return array|false|null|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getUserInfoByOpenId($open_id, $wechat){
        self::$user = Db::name('users')->alias('u')
            ->join('__USERS_WECHAT__ uw','uw.user_uniq=u.user_uniq','left')
            ->where('uw.openid', $open_id)
            ->where('uw.wechat',$wechat)
            ->field('u.user_uniq , u.login_name ,  u.nickname , uw.openid,uw.wechat')
            ->find();
        Session::set('user_info', self::$user , PROJECT_NAME);

        return self::$user;
    }

    /**
     * @param $login_name
     * @param $wechat
     * @return array|false|null|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getUserInfoByLoginName($login_name, $wechat){
        self::$user = Db::name('users')->alias('u')
            ->join('__USERS_WECHAT__ uw','uw.user_uniq=u.user_uniq','left')
            ->where('u.login_name',$login_name)
            ->where('uw.wechat',$wechat)
            ->field('u.user_uniq,u.login_name,u.nickname,,uw.openid,uw.wechat')
            ->find();
        Session::set('user_info',self::$user , PROJECT_NAME);
        return self::$user;
    }

    public static function wechat($user_uniq , $openid , $wechat){
        $users_wechat = [
            'openid'=>$openid,
            'wechat'=>$wechat,
            'user_uniq'=>$user_uniq,
            'timestamp'=>time()
        ];
        // 建立该用户与当前微信公众号的关联记录
        return Db::name('users_wechat')->insert($users_wechat,true);
    }
}