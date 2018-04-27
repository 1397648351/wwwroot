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


class GoodsOrder extends Base
{
    public function addInfo($goods,$userId,$outTradeNo,$payType)
    {
        $data = array();
        $data['goods_id'] = $goods['id'];
        $data['out_trade_no'] = $outTradeNo;
        $data['user_id'] = $userId;
        $data['type'] = $payType=='wx'?1:2;
        $data['status'] = 0;
        $data['money'] = $goods['price'];
        $data['create_time'] = time();
        $res = $this->save($data);
        if($res){
            return $this->id;
        }else{
            return false;
        }
    }

    public function findByOutTradeNo($outTradeNo)
    {
        $map = array();
        $map['out_trade_no'] = $outTradeNo;
        return $this->findByWhere($map);
    }

    /**
     * @param $out_trade_no
     * @param $openid
     * @param $result_code
     * @return false|int
     * @author LiuTao liut1@kexinbao100.com
     */
    public function updateInfo($out_trade_no, $openid, $result_code)
    {
        $map = array();
        $map['out_trade_no'] = $out_trade_no;
        $data = array();
        $data['status'] = $result_code;
        $data['openid'] = $openid;
        $data['update_time'] = time();
        return $this->updateDataByMap($data, $map);
    }
}