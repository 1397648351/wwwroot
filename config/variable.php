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
        'app_id' => '101474732',
        'app_secret' => 'ef07ca0137b27942c3d0efa36d93d4c4',
        'back_url' => 'http://www.picagene.com/user/qqLogin'
    ),

    //云信
    'yunConfig' => array(
        'app_id' => '660dc5e6110f25d94186ff946cacfdfa',
        'app_key' => '0f254164ce23',
        'login_template_id' => '3952755',
        'register_template_id' => '3902746',
    ),
];