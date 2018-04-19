<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/3/22
// +----------------------------------------------------------------------

namespace app\home\controller;

use org\Sms;
use org\Wechat;
use Yurun\OAuthLogin\QQ;
use Yurun\OAuthLogin\Weixin;
use app\common\controller\BaseController;

class UserController extends BaseController
{
    //loginType 1:三方登录   2：账号(或邮箱)密码登录 3、手机登录
    public function login()
    {
        $req = $this->request;
        if (!$req->isPost()) {
            return $this->fetch('login');
        }
        $loginType = $this->checkEmpty($req->param('loginType'), 'loginType不能为空');
        $user = array();
        switch ($loginType) {
            case 1:
                $user = $this->normalLogin();
                break;
            case 2:
                $user = $this->mobileLogin();
                break;
            case 3:
                $user = $this->quickLogin();
                break;
            default:
                $this->resJson(array(), 1004, '登录类型错误');
        }
        if (empty($user)) {
            $this->resJson($user, 1000, '用户不存在');
        }
        session('userInfo', $user);
        $this->resJson($user, 200, '登录成功');
    }

    /**
     * 三方登录跳转二维码
     * @author LiuTao liut1@kexinbao100.com
     */
    public function qrLogin()
    {
        $wxOAuthConfig = config('variable.wxOAuthConfig');
        $wxOAuth = new Weixin\OAuth2($wxOAuthConfig['app_id'], $wxOAuthConfig['app_secret'], $wxOAuthConfig['back_url']);
        $url = $wxOAuth->getAuthUrl();
        $this->redirect($url);
//        $type = $_GET['type'];
//        $baseUrl = urlencode('http://www.picagene.com');
//        $wxAppId = config('variable.wx_app_id');
//        if($type == 'wechat'){
//            $url = "https://open.weixin.qq.com/connect/qrconnect?appid=".$wxAppId."&redirect_uri=".$baseUrl."&response_type=code&scope=snsapi_login&state=picagene#wechat_redirect";
//        } else {
//            $url = "";
//        }
//        $this->redirect($url);
    }

    public function wxLogin()
    {
        $code = $_GET['code'];
        $wxAppId = config('variable.wx_app_id');;
        $wxAppSecret = config('variable.wx_app_id');
        $wechat = new Wechat($wxAppId, $wxAppSecret);
        $res = $wechat->getOauthAccessToken($code);
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$wxAppId.'&secret='.$wxAppSecret.'&code='.$code.'&grant_type=authorization_code';
        $info = $this->getOauthAccessToken($code, $url);
        $openid = $res['openid'];
        $access_token = $res['access_token'];
        $userInfo = $wechat->getOauthUserinfo($access_token, $openid);
        dump($userInfo);
    }

    /**
     * 退出
     * @author LiuTao liut1@kexinbao100.com
     */
    public function logout()
    {
        session(null);
        $this->redirect('Index/index');
    }

    /**
     * 注册
     * @return mixed
     * @author LiuTao liut1@kexinbao100.com
     */
    public function register()
    {
        $req = $this->request;
        if (!$req->isPost()) {
            return $this->fetch('register');
        }
        $username = $this->checkEmpty($req->param('username'), 'username不能为空');
        $password = $this->checkEmpty($req->param('password'), 'password不能为空');
        $mobile = $this->checkMobile($req->param('mobile'), 'mobile不能为空');
        $verification_code = $this->checkEmpty($req->param('verification_code'), 'verification_code不能为空');
        $sms = new Sms();
        $res = $sms->verify($mobile, $verification_code);
        if (empty($res)) {
            $this->resJson(array(), 1003, '验证码错误');
        }
        $userModel = model('user');
        $userId = $userModel->addInfo($username, md5($password), $mobile);
        if ($res) {
            $user = $userModel->find($userId);
            $this->resJson($user, '200', '注册成功');
        } else {
            $this->resJson(array(), '5001', '注册失败');
        }
    }

    /**
     * 重置密码
     * @return mixed
     * @author LiuTao liut1@kexinbao100.com
     */
    public function resetPwd()
    {
        $req = $this->request;
        if (!$req->isPost()) {
            return $this->fetch('reset');
        }
        $mobile = $this->checkMobile($req->param('mobile'));
        $password = $this->checkEmpty($req->param('password'), 'password不能为空');
        $verification_code = $this->checkEmpty($req->param('verification_code'), 'verification_code不能为空');
        $sms = new Sms();
        $res = $sms->verify($mobile, $verification_code);
        if (empty($res)) {
            $this->resJson(array(), 1003, '验证码错误');
        }
        $userModel = model('user');
        $result = $userModel->resetPwd($mobile, md5($password));
        if ($result) {
            $this->resJson($result, 200, '重置成功');
        }
        $this->resJson(array(), 5001, '重置密码失败');
    }

    /*-----------------/私有方法/----------------------*/

    /**
     * 手机验证码登录
     * @author LiuTao liut1@kexinbao100.com
     */
    private function mobileLogin()
    {
        $req = $this->request;
        $mobile = $this->checkMobile($req->param('mobile'));
        $verificationCode = $this->checkEmpty($req->param('verification_code'), 'verification_code不能为空');
        $sms = new Sms();
        $res = $sms->verify($mobile, $verificationCode);
        if (empty($res)) {
            $this->resJson(array(), 1003, '验证码错误');
        }
        $userModel = model('user');
        $user = $userModel->findByMobile($mobile);
        return $user;
    }

    /**
     * 账号(或邮箱)密码登录
     * @author LiuTao liut1@kexinbao100.com
     */
    private function normalLogin()
    {
        $req = $this->request;
        $key = $this->checkEmpty($req->param('key'), 'key不能为空');
        $password = $this->checkEmpty($req->param('password'), 'password不能为空');
        $userModel = model('user');
        $user = $userModel->findByKeyAndPwd($key, md5($password));
        return $user;
    }

    /**
     * 微信、QQ快捷登录
     * @author LiuTao liut1@kexinbao100.com
     */
    private function quickLogin()
    {
//        $qqOAuthConfig = array();
        $wxOAuthConfig = config('variable.wxOAuthConfig');
//        $qqOAuth = new QQ\OAuth2($qqOAuthConfig['appId'], $qqOAuthConfig['appSecret'], $qqOAuthConfig['callbackUrl']);
        $wxOAuth = new Weixin\OAuth2($wxOAuthConfig['app_id'], $wxOAuthConfig['app_secret'], $wxOAuthConfig['back_url']);
        $url = $wxOAuth->getAuthUrl();
        $this->redirect($url);
        header('location'.$url);
    }

}