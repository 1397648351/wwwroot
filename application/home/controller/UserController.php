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
use app\common\controller\BaseController;
use Yurun\OAuthLogin\QQ\OAuth2;

class UserController extends BaseController
{
    public function index()
    {
        return $this->fetch('login');
    }

    //loginType 1：账号(或邮箱)密码登录  2、手机登录
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
            default:
                $this->resJson(array(), 1004, '登录类型错误');
        }
        if (empty($user)) {
            $this->resJson($user, 1000, '用户名或密码错误');
        }
        session('loginType', $loginType);
        session('userInfo', $user);
        if ($loginType == 1) {
            if ($this->request->param('remember') == 'true') {
                $usercookie = array();
                $usercookie['key'] = $this->request->param('key');
                $usercookie['password'] = $this->request->param('password');
                cookie('userInfo', $usercookie);
            } else {
                cookie('userInfo', null);
            }
        }
        $this->resJson($user, 200, '登录成功');
    }

    /**
     * 发送验证码
     * @param mobile 手机
     * @param type 类型   login  register
     * @author LiuTao liut1@kexinbao100.com
     */
    public function sendSms()
    {
        $req = $this->request;
        $mobile = $req->param('mobile');
        $type = $req->param('type');
        $yunXinConfig = config('variable.yunConfig');
        $appId = $yunXinConfig['app_id'];
        $appKey = $yunXinConfig['app_key'];
        if ($type == 'login') {
            $templateId = $yunXinConfig['login_template_id'];
        } else {
            $templateId = $yunXinConfig['register_template_id'];
        }
        $sms = new Sms($appId, $appKey);
        $res = $sms->sendSmsCode($mobile, '', $templateId);
        if ($res['code'] == 200) {
            $this->resJson(array(), 200, '发送成功');
        }
        $this->resJson($res, $res['code'], '发送失败');
    }

    /**
     * 三方登录跳转二维码
     * @author LiuTao liut1@kexinbao100.com
     */
    public function qrLogin()
    {
        $type = $_GET['type'];
        if ($type == 'wechat') {
            $wxOAuthConfig = config('variable.wxOAuthConfig');
            $baseUrl = urlencode($wxOAuthConfig['back_url']);
            $wxAppId = $wxOAuthConfig['app_id'];
            $url = "https://open.weixin.qq.com/connect/qrconnect?appid=" . $wxAppId . "&redirect_uri=" . $baseUrl . "&response_type=code&scope=snsapi_login&state=picagene#wechat_redirect";
        } else {
            $qqOAuthConfig = config('variable.qqOAuthConfig');
            $qqAppId = $qqOAuthConfig['app_id'];
            $qqAppSecret = $qqOAuthConfig['app_secret'];
            $baseUrl = $qqOAuthConfig['back_url'];
            $qqOAuth = new OAuth2($qqAppId, $qqAppSecret, $baseUrl);
            $url = $qqOAuth->getAuthUrl($baseUrl, 'picagene');
        }
        $this->redirect($url);
    }

    /**
     * 微信登录 用户信息保存
     * @author LiuTao liut1@kexinbao100.com
     */
    public function wxLogin()
    {
        $wxOAthConfig = config('variable.wxOAuthConfig');
        $wxAppId = $wxOAthConfig['app_id'];
        $wxAppSecret = $wxOAthConfig['app_secret'];
        $wechat = new Wechat($wxAppId, $wxAppSecret);
        $res = $wechat->getOauthAccessToken();
        $openid = $res['openid'];
        $unionid = $res['unionid'];
        $access_token = $res['access_token'];
        $userInfo = $wechat->getOauthUserinfo($access_token, $openid);
        $userModel = model('User');
        $user = $userModel->findByUnionid($unionid);
        if (empty($user)) {
            $res = $userModel->addInfoByWx($userInfo);
            $user = $userModel->findByUnionid($unionid);
        }
        session('userInfo', $user);
        $this->redirect('Index/index');
    }

    /**
     * QQ登录 用户信息保存
     * @author LiuTao liut1@kexinbao100.com
     */
    public function qqLogin()
    {
        $qqOAuthConfig = config('variable.qqOAuthConfig');
        $qqAppId = $qqOAuthConfig['app_id'];
        $qqAppSecret = $qqOAuthConfig['app_secret'];
        $baseUrl = $qqOAuthConfig['back_url'];
        $qqOAuth = new OAuth2($qqAppId, $qqAppSecret, $baseUrl);
        $accessToken = $qqOAuth->getAccessToken('picagene');
        $openid = $qqOAuth->getOpenID($accessToken);
        $userInfo = $qqOAuth->getUserInfo($accessToken);
        $userModel = model('User');
        $user = $userModel->findByQqOpenid($openid);
        if (empty($user)) {
            $res = $userModel->addInfoByQq($userInfo, $openid);
            $user = $userModel->findByQqOpenid($openid);
        }
        session('userInfo', $user);
        $this->redirect('Index/index');
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
        $verification_code = $this->checkEmpty($req->param('code'), 'code不能为空');
        $yunXinConfig = config('variable.yunConfig');
        $appId = $yunXinConfig['app_id'];
        $appKey = $yunXinConfig['app_key'];
        $sms = new Sms($appId, $appKey);
        $res = $sms->verifyCode($mobile, $verification_code);
        if (empty($res)) {
            $this->resJson(array(), 1003, '验证码错误');
        }
        $userModel = model('user');
        $user = $userModel->findByMobile($mobile);
        if (empty($user)) {
            $userId = $userModel->addInfo($username, md5($password), $mobile);
        } else {
            $res = $userModel->updateUserInfo($user['id'], $username, md5($password));
            $userId = $user['id'];
        }
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
            return $this->fetch('forget');
        }
        $mobile = $this->checkMobile($req->param('mobile'));
        $password = $this->checkEmpty($req->param('password'), 'password不能为空');
        $verification_code = $this->checkEmpty($req->param('code'), 'code不能为空');
        $yunXinConfig = config('variable.yunConfig');
        $appId = $yunXinConfig['app_id'];
        $appKey = $yunXinConfig['app_key'];
        $sms = new Sms($appId, $appKey);
        $res = $sms->verifyCode($mobile, $verification_code);
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
        $code = $this->checkEmpty($req->param('verification_code'), 'verification_code不能为空');
        $res = $this->verifyCode($mobile, $code);
        if (empty($res)) {
            $this->resJson(array(), 1003, '验证码错误');
        }
        $userModel = model('user');
        $user = $userModel->findByMobile($mobile);
        if (empty($user)) {
            $res = $userModel->addUserByMobile($mobile);
            $user = $userModel->find($res);
        }
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
     * 短信验证
     * @param $mobile 手机号
     * @param $code 验证码
     * @return bool
     * @author LiuTao liut1@kexinbao100.com
     */
    private function verifyCode($mobile, $code)
    {
        $yunXinConfig = config('variable.yunConfig');
        $appId = $yunXinConfig['app_id'];
        $appKey = $yunXinConfig['app_key'];
        $sms = new Sms($appId, $appKey);
        $res = $sms->verifycode($mobile, $code);
        if ($res['code'] == 200) {
            return true;
        }
        return false;
    }
}