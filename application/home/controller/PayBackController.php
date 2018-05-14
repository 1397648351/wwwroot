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

use app\common\controller\BaseController;
use Payment\Client\Notify;
use think\facade\Log;

/**
 * 支付回调处理
 * Class PayBackController
 * @package app\home\controller
 */
class PayBackController extends BaseController
{
    /**
     * 微信支付回调
     * @author LiuTao liut1@kexinbao100.com
     */
    public function wxBack()
    {
        $type = 'wx_charge';
        $config = $this->wxConfigData();
        $ret =  Notify::getNotifyData($type, $config);
        Log::write(json_encode($ret));
        $openid = $ret['openid'];
        $out_trade_no = $ret['out_trade_no'];
        $result_code = $ret['result_code']=='SUCCESS' ? 1 : -1;
        $goodsOrderModel = model('goodsOrder');
        $goodsOrderInfo = $goodsOrderModel->findByOutTradeNo($out_trade_no);
        if($result_code == $goodsOrderInfo['status']){
            $this->resJson(array(), 200);
        } else {
            $res = $goodsOrderModel->updateInfo($out_trade_no, $openid, $result_code);
        }
        $this->resJson(array(), 200);
    }



    /**
     * 支付宝支付回调
     * @author LiuTao liut1@kexinbao100.com
     */
    public function aliBack()
    {

    }
}