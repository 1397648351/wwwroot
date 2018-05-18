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

class GoodsOrder extends Base
{
    public function addInfo($goods, $userId, $outTradeNo, $payType, $num)
    {
        $data = array();
        $data['goods_id'] = $goods['id'];
        $data['out_trade_no'] = $outTradeNo;
        $data['user_id'] = $userId;
        $data['type'] = $payType;
        $data['status'] = 0;
        $data['money'] = $goods['price'];
        $data['num'] = $num;
        $data['create_time'] = time();
        $res = $this->save($data);
        if ($res) {
            return $this->id;
        } else {
            return false;
        }
    }

    public function findByOutTradeNo($outTradeNo)
    {
        $map = array();
        $map['out_trade_no'] = $outTradeNo;
        return $this->findByWhere($map);
    }

    public function findOrderInfo($orderId)
    {
        $sql = "select o.id,o.out_trade_no,g.subject,a.username,a.mobile 
              from ge_address a,ge_goods g,ge_goods_order o where o.id=a.goods_order_id and o.goods_id=g.id and o.id=" . $orderId;
        return $this->query($sql);
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

    public function findOrderByUser($userId, $page = true)
    {
        $map = array();
        $map['a.user_id'] = $userId;
        $fields = 'a.out_trade_no as no,a.user_id,a.num,a.money,b.subject,a.create_time';
        if($page)
            $data = $this->alias('a')->join('goods b', 'a.goods_id=b.id', 'LEFT')->field($fields)->where($map)->order('a.create_time', 'desc')->paginate(10);
        else
            $data = $this->alias('a')->join('goods b', 'a.goods_id=b.id', 'LEFT')->field($fields)->where($map)->order('a.create_time', 'desc')->select();
        return $data;
    }
}