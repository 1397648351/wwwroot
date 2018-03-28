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

/**
 * 下单支付处理
 * Class OrderController
 * @package app\home\controller
 */
class OrderController extends BaseController
{
    public function order()
    {
        $req = $this->request;

    }

    public function aliPay()
    {
        $param = $this->request->param();
        try {
            $str = Charge::run('ali_wap',$this->aliPayConfigData(), $this->setAliPublicParam($param));
        } catch (PayException $e){
            echo $e->errorMessage();
            exit;
        }
    }

    /**
     * 支付宝配置
     * @return array
     * @author LiuTao liut1@kexinbao100.com
     */
    private function aliPayConfigData()
    {
        $data = array();
        $data['use_sandbox'] = true;
        $data['partner'] = '';//收款支付宝用户ID(2088开头)
        $data['app_id'] = '';
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
     * 支付宝支付公共参数
     * @param $param
     * @author LiuTao liut1@kexinbao100.com
     */
    private function setAliPublicParam($param)
    {
        //商品具体描述
        $body = $param['body'];
        //商品标题
        $subject = $param['subject'];
        //订单号
        $order_no = '订单号';
        //需要支付金额 元
        $amount = $param['amount'];
        //过期时间（当前时间+过期s数） 时间戳
        $timeout_express = 3600+time();
        $return_param = '';
        //商品类型  0：虚拟 1：实物
        $goods_type = $param['goods_type'];
        //门店标记
        $store_id = $param['store_id'];
    }
}