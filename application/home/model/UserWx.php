<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/5/2
// +----------------------------------------------------------------------

namespace app\home\model;

use app\common\model\Base;

class UserWx extends Base
{
    public function addInfo($wxUser)
    {
        $data = array();
        $data['openid'] = $wxUser['openid'];
        $data['nickname'] = $wxUser['nickname'];
        $data['sex'] = $wxUser['sex'];
        $data['headimgurl'] = $wxUser['headimgurl'];
        $data['create_time'] = time();
        $res = $this->save($data);
        if ($res) {
            return $this->id;
        } else {
            return false;
        }
    }

    public function findByOpenId($openid)
    {
        $map = array();
        $map['openid'] = $openid;
        return $this->findByWhere($map);
    }

    public function updateInfo($id, $wxUser)
    {
        $map = array();
        $map['id'] = $id;
        $data = array();
        $data['nickname'] = $wxUser['nickname'];
        $data['headimgurl'] = $wxUser['headimgurl'];
        $data['update_time'] = time();
        $res = $this->updateDataByMap($data, $map);
    }
}