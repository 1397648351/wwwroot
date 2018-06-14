<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/6/14
// +----------------------------------------------------------------------

namespace app\other\controller;


use app\common\controller\BaseController;
use org\Wechat;

class PublicController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        if($this->request->isGet()) {
            $this->getJsSign();
        }
    }

    /**
     * 获取js-sdk签名
     */
    public function getJsSign()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $wxOAuthConfig = config('variable.wxMobile');
        $wxAppId = $wxOAuthConfig['app_id'];;
        $wxAppSecret = $wxOAuthConfig['app_secret'];
        $Wechat = new Wechat($wxAppId, $wxAppSecret);
        $signPackage = $Wechat->getJsSign($url);
        $this->assign('signPackage', $signPackage);
    }
}