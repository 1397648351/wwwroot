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
        $fields = 'a.out_trade_no as no,a.user_id,a.money,b.subject,a.create_time';
        $offset = ($page - 1) * $rows;
        $total = $this->count();
        $data = $this->alias('a')->join('goods b', 'a.goods_id=b.id', 'LEFT')->field($fields)->order('a.create_time', 'desc')->limit($offset, $rows)->select();
        $result['total'] = $total;
        $result['data'] = $data;
        return $result;
    }
}