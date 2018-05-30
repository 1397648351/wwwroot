<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/4/27
// +----------------------------------------------------------------------

namespace app\home\model;

use app\common\model\Base;

class Address extends Base
{
    public function addInfo($username, $mobile, $email, $city, $detailAdd, $goodsOrderId, $invoiceType, $invoiceTitle, $payTaxesId, $userMsg)
    {
        $data = array();
        $data['username'] = $username;
        $data['mobile'] = $mobile;
        $data['city'] = $city;
        $data['email'] = $email;
        $data['detail_address'] = $detailAdd;
        $data['goods_order_id'] = $goodsOrderId;
        $data['invoice_type'] = $invoiceType;
        $data['invoice_title'] = $invoiceTitle;
        $data['pay_taxes_id'] = $payTaxesId;
        $data['user_msg'] = $userMsg;
        $data['create_time'] = time();
        $res = $this->save($data);
        if ($res) {
            return $this->id;
        } else {
            return false;
        }
    }
}