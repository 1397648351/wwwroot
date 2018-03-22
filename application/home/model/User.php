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


class User extends Base
{
    public function addInfo($username, $password, $mobile)
    {
        $data = array();
        $data['name'] = $username;
        $data['password'] = $password;
        $data['mobile'] = $mobile;
        $data['create_time'] = time();
        $res = $this->save($data);
        if($res){
            return $this->id;
        }else{
            return false;
        }
    }

    public function findByMobile($mobile)
    {
        $map = array();
        $map['mobile'] = $mobile;
        return $this->findByWhere($map);
    }

    public function findByKeyAndPwd($key, $pwd)
    {
        $map = array();
        $map['name|mail'] = $key;
        $map['password'] = $pwd;
        return $this->findByWhere($map);
    }
}