<?php
/**
 * Created by PhpStorm.
 * User: 吴泽
 * Date: 2018/5/9
 * Time: 17:27
 */

namespace app\admin\model;

use app\common\model\Base;

class GoodsOrder extends Base
{
    public function findAll($page, $rows)
    {
        $fields = 'a.out_trade_no as no,a.user_id,c.username,a.money,a.goods_id,b.subject,a.create_time,c.mobile,c.email,c.city,c.detail_address,d.name as status';
        $offset = ($page - 1) * $rows;
        $total = $this->count();
        $data = $this->alias('a')->join('goods b', 'a.goods_id=b.id', 'LEFT')->join('address c', 'a.id=c.goods_order_id', 'LEFT')->join('status d', 'a.status=d.id', 'LEFT')->field($fields)->order('a.create_time', 'desc')->limit($offset, $rows)->select();
        $result['total'] = $total;
        $result['data'] = $data;
        return $result;
    }

    public function getOrderList()
    {
        $fields = 'a.out_trade_no as no,a.user_id,c.username,a.money,a.goods_id,b.subject,a.create_time,c.mobile,c.email,c.city,c.detail_address,d.name as status';
        $data = $this->alias('a')->join('goods b', 'a.goods_id=b.id', 'LEFT')->join('address c', 'a.id=c.goods_order_id', 'LEFT')->join('status d', 'a.status=d.id', 'LEFT')->field($fields)->order('a.create_time', 'desc')->select();
        return $data;
    }
}