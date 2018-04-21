<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/4/21
// +----------------------------------------------------------------------

namespace app\home\model;


class UserQq extends Base
{
    public function addInfo($qqUser)
    {
        $data = array();
        $data['openid'] = $qqUser['openid'];
        $data['nickname'] = $qqUser['nickname'];
        $data['sex'] = $qqUser['sex'];
        $data['headimgurl'] = $qqUser['headimgurl'];
        $data['create_time'] = time();
        $res = $this->save($data);
        if($res){
            return $this->id;
        }else{
            return false;
        }
    }

}