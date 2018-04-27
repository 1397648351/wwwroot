<?php

namespace app\common\controller;

use think\Controller;

class BaseController extends Controller {
	function initialize() {
		//echo 'aa<br />';
	}

    public function checkMobile($mobile)
    {
        $this->checkEmpty($mobile, 'mobile不能为空');
        if(!preg_match('/^1[0-9]{10}$/', $mobile)){
            $this->resJson($mobile,1011, '手机号格式错误');
        }
        return $mobile;
    }

    /**
     * 非空验证
     * @param $data
     * @param string $describe
     * @param $type
     * @return mixed
     * @author LiuTao liut1@kexinbao100.com
     */
    public function checkEmpty($data, $describe = '参数不能为空')
    {
        if(empty($data)){
            $this->resJson(array(), 1002, $describe);
        }
        return $data;
    }

    /**
     * json返回
     * @param $data 数据
     * @param int $status 状态码
     * @param int $msg 描述
     * @author LiuTao liut1@kexinbao100.com
     */
    public function resJson($data = array(), $status_code = 1001, $msg = 0)
    {
        header('Content-Type:application/json; charset=utf-8');
        $res['data'] = $data;
        $res['status_code'] = $status_code;
        $res['msg'] = $msg;
        $str = json_encode($res, JSON_UNESCAPED_UNICODE);
        exit($str);
    }

    /**
     * 返回流水
     * @param null $form
     * @return string
     * @author LiuTao liut1@kexinbao100.com
     */
    public function getMgid($form = null)
    {
        list($usec, $sec) = explode(" ", microtime());
        $usec = substr(str_replace('0.', '', $usec), 0, 4);
        $mm = date("Ym");
        if (empty($form)) {
            $str = $this->rand_string(15, 1);
            return $mm . $usec . $str;
        } else {
            if (strlen($form) > 6) {
                $form = substr($form, 0, 6);
            }
            $form = $form . $mm;
            $str = $this->rand_string(25 - strlen($form) - strlen($usec), 1);
            return $form . $usec . $str;
        }
    }

    /**
     * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
     * @param string $len 长度
     * @param string $type 字串类型
     * 0 字母 1 数字 其它 混合
     * @param string $addChars 额外字符
     * @return string
     * @author LiuTao liut1@kexinbao100.com
     */
    public function rand_string($len = 6, $type = '', $addChars = '')
    {
        $str = '';
        switch ($type) {
            case 0:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 1:
                $chars = str_repeat('0123456789', 3);
                break;
            case 2:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
                break;
            case 3:
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            default :
                // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
                break;
        }
        if ($len > 10) { //位数过长重复字符串一定次数
            $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
        }
        if ($type != 4) {
            $chars = str_shuffle($chars);
            $str = substr($chars, 0, $len);
        } else {
            // 中文随机字
            for ($i = 0; $i < $len; $i++) {
                $str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
            }
        }
        return $str;
    }

    /**
     * 支付宝配置
     * @return array
     * @author LiuTao liut1@kexinbao100.com
     */
    protected function aliConfigData()
    {
        $data = array();
        $data['use_sandbox'] = true;
        $data['partner'] = config('variable.aliPayConfig.partner');//收款支付宝用户ID(2088开头)
        $data['app_id'] = config('variable.aliPayConfig.app_id');
        $data['sign_type'] = 'RSA2'; //签名方式
        $data['ali_public_key'] = '';
        $data['rsa_private_key'] = '';
        $data['limit_pay'] = array();
        $data['notify_url'] = '';//异步回调url
        $data['return_url'] = '';//同步通知回调url
        $data['return_raw'] = 'true';
        return $data;
    }

    /**
     * 微信配置
     * @author LiuTao liut1@kexinbao100.com
     */
    protected function wxConfigData()
    {
        $data = array();
        //微信支付验收模式
        $data['use_sendbox'] = true;
        $data['app_id'] = config('variable.wxPayConfig.app_id');
        //微信支付商户号
        $data['mch_id'] = config('variable.wxPayConfig.mch_id');
        //商户中心配置
        $data['md5_key'] = config('variable.wxPayConfig.key');
        //证书pem路径
        $data['app_cert_pem'] = ''; //../extend/org/Wx/cert/apiclient_cert.pem
        //证书秘钥pem路径
        $data['app_key_pem'] = ''; //../extend/org/Wx/cert/apiclient_cert.pem
        //签名方式 MD5 HMAC-SHA256
        $data['sign_type'] = 'MD5';
        $data['limit_pay'] = array('no_credit');
        $data['fee_type'] = 'CNY';
        //异步回调url
        $data['notify_url'] = 'http://liutaolight.mynatapp.cc/wwwroot/public/PayBack/wxBack';
        //同步通知回调url
        $data['redirect_url'] = 'http://www.picagene.com';
        $data['return_raw'] = 'true';
        return $data;
    }
}