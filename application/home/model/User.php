<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/3/22
// +----------------------------------------------------------------------

namespace app\home\model;

use app\common\model\Base;

class User extends Base
{
    /**
     * 注册新用户
     * @param $username
     * @param $password
     * @param $mobile
     * @return bool|mixed
     * @author LiuTao liut1@kexinbao100.com
     */
    public function addInfo($username, $password, $mobile)
    {
        $data = array();
        $data['nickname'] = $username;
        $data['password'] = $password;
        $data['mobile'] = $mobile;
        $data['create_time'] = time();
        $res = $this->save($data);
        if ($res) {
            return $this->id;
        } else {
            return false;
        }
    }

    public function findByOpenid($openid, $type)
    {
        $map = array();
        if ($type == 'wx') {
            $map['wx_openid'] = $openid;
        } else {
            $map['qq_openid'] = $openid;
        }
        return $this->findByWhere($map);
    }

    public function addInfoByWx($user)
    {
        $data = array();
        $data['wx_openid'] = $user['openid'];
        $data['avatar'] = $user['headimgurl'];
        $data['nickname'] = $user['nickname'];
        $data['sex'] = $user['sex'];
        $data['create_time'] = time();
        $res = $this->save($data);
        if ($res) {
            return $this->id;
        } else {
            return false;
        }
    }

    public function addInfoByQq($user, $openid)
    {
        $data = array();
        $data['qq_openid'] = $openid;
        $data['avatar'] = $user['figureurl_qq_2'];
        $data['nickname'] = $user['nickname'];
        $data['sex'] = $user['gender'] == '男' ? 1 : 2;
        $data['create_time'] = time();
        $res = $this->save($data);
        if ($res) {
            return $this->id;
        } else {
            return false;
        }
    }

    /**
     * @param $mobile
     * @return array|null|\PDOStatement|string|\think\Model
     * @author LiuTao liut1@kexinbao100.com
     */
    public function findByMobile($mobile)
    {
        $map = array();
        $map['mobile'] = $mobile;
        return $this->findByWhere($map);
    }

    /**
     * @param $key
     * @param $pwd
     * @return array|null|\PDOStatement|string|\think\Model
     * @author LiuTao liut1@kexinbao100.com
     */
    public function findByKeyAndPwd($key, $pwd)
    {
        $map = array();
        $map['nickname|mail'] = $key;
        $map['password'] = $pwd;
        return $this->findByWhere($map);
    }

    /**
     * 重置密码
     * @param $mobile
     * @param $password
     * @return false|int
     * @author LiuTao liut1@kexinbao100.com
     */
    public function resetPwd($mobile, $password)
    {
        $map = array();
        $map['mobile'] = $mobile;
        $data = array();
        $data['password'] = $password;
        $data['update_time'] = time();
        return $this->updateDataByMap($data, $map);
    }

    public function updateInfo($id,$wxUser)
    {
        $map = array();
        $map['id'] = $id;
        $data = array();
        $data['nickname'] = $wxUser['nickname'];
        $data['avatar'] = $wxUser['headimgurl'];
        $data['update_time'] = time();
        $res = $this->updateDataByMap($data, $map);
    }
}