<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/3/22
// +----------------------------------------------------------------------

namespace org;

/**
 * 接入短信接口
 * Class Sms
 * @package org
 */
class Sms
{
    public function send($mobile)
    {

    }

    public function verify($mobile, $verification_code)
    {
        return true;
    }

}