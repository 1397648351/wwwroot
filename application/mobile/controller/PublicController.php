<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/5/2
// +----------------------------------------------------------------------

namespace app\mobile\controller;


use app\common\controller\BaseController;
use org\Wechat;

class PublicController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $openid = session('openid');
        $code = $this->request->param('code');
        $url = $this->getBaseUrl().url();
        if(empty($openid)){
            $wxOAuthConfig = config('variable.wxOAuthConfig');
            $wxAppId = $wxOAuthConfig['app_id'];;
            $wxAppSecret = $wxOAuthConfig['app_secret'];
            $Wechat = new Wechat($wxAppId, $wxAppSecret);
            if(empty($code)){
                redirect($Wechat->getOauthRedirect($url, 'picagene', 'snsapi_userinfo'));
            }
            $info = $Wechat->getOauthAccessToken();
            if ($info['openid']) {
                session('openid', $info['openid']);
                $this->saveUserInfo($info);
            }
        }
    }

    /**
     * 获取js-sdk签名
     */
    public function getJsSign()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $wxOAuthConfig = config('variable.wxOAuthConfig');
        $wxAppId = $wxOAuthConfig['app_id'];;
        $wxAppSecret = $wxOAuthConfig['app_secret'];
        $Wechat = new Wechat($wxAppId, $wxAppSecret);
        $signPackage = $Wechat->getJsSign($url);
        $this->assign('signPackage', $signPackage);
    }

    private function saveUserInfo($info)
    {
        $wxOAuthConfig = config('variable.wxOAuthConfig');
        $wxAppId = $wxOAuthConfig['app_id'];;
        $wxAppSecret = $wxOAuthConfig['app_secret'];
        $Wechat = new Wechat($wxAppId, $wxAppSecret);
        $userInfo = $Wechat->getOauthUserinfo($info['access_token'], $info['openid']);
        if(!empty($userInfo)) {
            $userWxModel = model('UserWx');
            $user = $userWxModel->findByOpenId($info['openid']);
            if (empty($user)) {
                $res = $userWxModel->addInfo($info);
            } else {
                $res = $userWxModel->updateInfo($user['id'],$userInfo);
            }
        }
    }
}