<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/3/25
// +----------------------------------------------------------------------

namespace app\home\controller;

use Payment\Client\Charge;
use Payment\Common\PayException;
use app\common\controller\BaseController;

/**
 * 下单支付处理
 * Class OrderController
 * @package app\home\controller
 */
class OrderController extends BaseController
{
    public function order()
    {
        //$req = $this->request;
        if(!session('?username')){
            $this->error("请先登录！",'User/login');
            //$this->redirect('User/login');
        }
        $this->assign("price", 449);
        if (session('?username')) {
            $this->assign("username", session('username'));
        }
        return $this->fetch();
    }

    public function pay()
    {
        $payType = $this->request->param('pay_type');
        $payParams = $this->setParam($payType);
        try {
            $str = Charge::run($payType['type'], $payParams['config'], $payParams['pay_param']);
        } catch (PayException $e) {
            echo $e->errorMessage();
            exit;
        }
    }

    /**
     * 支付参数设置
     * @param $payType
     * @return array
     * @author LiuTao liut1@kexinbao100.com
     */
    public function setParam($payType)
    {
        $params = array();
        switch ($payType) {
            case 'ali':
                $params['type'] = 'ali_wap';
                $params['config'] = $this->aliConfigData();
                $params['pay_param'] = $this->setAliPayParam();
                break;
            case 'weixin':
                $params['type'] = 'wx_wap';
                $params['config'] = $this->wxConfigData();
                $params['pay_param'] = $this->setWxPayParam();
                break;
            default:
                break;
        }
        if (empty($params)) {
            $this->resJson(array(), 2001, '支付类型不存在');
        }
        return $params;
    }

    /**
     * 支付宝支付公共参数
     * @param $param
     * @author LiuTao liut1@kexinbao100.com
     */
    private function setAliPayParam()
    {
        $payParam = array();
        $req = $this->request;
        //商品具体描述
        $payParam['body'] = $req->param('body');
        //商品标题
        $payParam['subject'] = $req->param('subject');
        //订单号
        $payParam['order_no'] = '订单号';
        //需要支付金额 元
        $payParam['amount'] = $req->param('amount');
        //过期时间（当前时间+过期s数） 时间戳
        $payParam['timeout_express'] = 3600 + time();
        $payParam['return_param'] = '';
        //商品类型  0：虚拟 1：实物  否
        $payParam['goods_type'] = $req->param('goods_type');
        //门店标记  否
        $payParam['store_id'] = $req->param('store_id');
        return $payParam;
    }

    /**
     * @return array
     * @author LiuTao liut1@kexinbao100.com
     */
    private function setWxPayParam()
    {
        $payParam = array();
        $req = $this->request;
        $payParam['body'] = $req->param('body');
        $payParam['subject'] = $req->param('subject');
        $payParam['order_no'] = '订单号';
        //单位 元
        $payParam['amount'] = $req->param('amount');
        //用户客户端实际IP地址
        $payParam['client_ip'] = $req->param('client_ip');
        $payParam['timeout_express'] = 3600 + time();
        //异步通知原样返回数据
        $payParam['return_param'] = 'pica';
        $payParam['type'] = 'Wap';
        //wap网站的url地址
        $payParam['wap_url'] = '';
        //wap网站名称
        $payParam['wap_name'] = '基因检测';
        return $payParam;
    }

    /**
     * 支付宝配置
     * @return array
     * @author LiuTao liut1@kexinbao100.com
     */
    private function aliConfigData()
    {
        $data = array();
        $data['use_sandbox'] = true;
        $data['partner'] = config('aliPayConfig.partner');//收款支付宝用户ID(2088开头)
        $data['app_id'] = config('aliPayConfig.app_id');
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
    private function wxConfigData()
    {
        $data = array();
        //微信支付验收模式
        $data['use_sendbox'] = true;
        $data['app_id'] = config('wxPayConfig.app_id');
        //微信支付商户号
        $data['mch_id'] = config('wxPayConfig.mch_id');
        //商户中心配置
        $data['md5_key'] = config('wxPayConfig.key');
        //证书pem路径
        $data['app_cert_pem'] = '../extend/org/Wx/cert/apiclient_cert.pem';
        //证书秘钥pem路径
        $data['app_key_pem'] = '../extend/org/Wx/cert/apiclient_cert.pem';
        //签名方式 MD5 HMAC-SHA256
        $data['sign_type'] = 'MD5';
        $data['limit_pay'] = array('no_credit');
        $data['fee_type'] = 'CNY';
        //异步回调url
        $data['notify_url'] = '';
        //同步通知回调url
        $data['redirect_url'] = '';
        $data['return_raw'] = 'true';
        return $data;
    }
}