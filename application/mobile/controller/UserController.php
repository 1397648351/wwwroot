<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 2018/7/2
 * Time: 10:11
 */

namespace app\mobile\controller;


use org\Sms;
class UserController extends PublicController
{
    public function index()
    {
        return $this->fetch('login');
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
     * 手机验证码登录
     * @author LiuTao liut1@kexinbao100.com
     */
    public function login()
    {
        $req = $this->request;
        $mobile = $this->checkMobile($req->param('mobile'));
        $code = $this->checkEmpty($req->param('code'), 'code不能为空');
        $res = $this->verifyCode($mobile, $code);
        if (empty($res)) {
            $this->resJson(array(), 1003, '验证码错误');
        }
        $userModel = model('home/user');
        $user = $userModel->findByMobile($mobile);
        if (empty($user)) {
            $res = $userModel->addUserByMobile($mobile);
            $user = $userModel->find($res);
        }
        if (empty($user)) {
            $this->resJson($user, 1000, '用户名或密码错误');
        }
        session('userInfo', $user);
        $this->resJson($user, 200, '登录成功');
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