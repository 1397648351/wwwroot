<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/5/15
// +----------------------------------------------------------------------

namespace app\mobile\controller;


use app\common\controller\BaseController;

class TestController extends BaseController
{
    public function index()
    {
        $openid = session('openid');
        $code = $this->request->param('code');
        $url = $this->getBaseUrl().url();
        if(empty($openid)){
            $wxOAuthConfig = config('variable.wxMobile');
            $wxAppId = $wxOAuthConfig['app_id'];;
            $wxAppSecret = $wxOAuthConfig['app_secret'];
            $Wechat = new Wechat($wxAppId, $wxAppSecret);
            if(empty($code)){
                $res = $Wechat->getOauthRedirect($url, 'picagene', 'snsapi_userinfo');
                $this->redirect($res);
            }
            $info = $Wechat->getOauthAccessToken();
            if ($info['openid']) {
                session('openid', $info['openid']);
                $this->test($info);
            }
        }
    }

    public function test($info)
    {
        $wxOAuthConfig = config('variable.wxMobile');
        $wxAppId = $wxOAuthConfig['app_id'];;
        $wxAppSecret = $wxOAuthConfig['app_secret'];
        $Wechat = new Wechat($wxAppId, $wxAppSecret);
        $userInfo = $Wechat->getOauthUserinfo($info['access_token'], $info['openid']);
        dump($userInfo);
    }
}