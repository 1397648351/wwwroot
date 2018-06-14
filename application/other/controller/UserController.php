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


use org\Sms;

class UserController extends PublicController
{
    //loginType 1：账号(或邮箱)密码登录  2、手机登录
    public function login()
    {
        $req = $this->request;
        if (!$req->isPost()) {
            return $this->fetch('login');
        }
        $user = $this->mobileLogin();
        if (empty($user)) {
            $this->resJson($user, 1000, '用户不存在');
        }
        session('userInfo', $user);
        $this->resJson($user, 200, '登录成功');
    }

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