<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 2018/7/4
 * Time: 16:52
 */

namespace app\admin\model;

use app\common\model\Base;

class Address extends Base
{
    public function updateInfo($id, $data)
    {
        $num = $this->where('goods_order_id', $id)->count();
        $data['update_time'] = time();
        if ($num > 0) $this->where('goods_order_id', $id)->update($data); else {
            $data['create_time'] = time();
            $data['goods_order_id'] = $id;
            $this->insert($data);
        }
    }
}