<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/4/17
// +----------------------------------------------------------------------

return [

    //微信商户
    'wxPayConfig' => array(
        'key' => 'Ude487XydqwpuPU83NFb67xnry9utn34',
        'app_id' => 'wxbf9c8fbe1173ff7d',
        'mch_id' => '1502488301'
    ),

    //支付宝支付
    'aliPayConfig' => array(
        'partner' => '',
        'app_id' => '',
    ),

    //微信登录
    'wxOAuthConfig' => array(
        'app_id' => 'wx157864b4de047dad',
        'app_secret' => 'ee7a31ac117cc19354b686cf6c696a24',
        'back_url' => 'http://www.picagene.com/user/wxLogin'
    ),

    //QQ登录
    'qqOAuthConfig' => array(
        'app_id' => '',
        'app_secret' => '',
        'back_url' => ''
    ),
];