<?php
/**
 * Created by PhpStorm.
 * User: WUZE
 * Date: 2018/6/13
 * Time: 22:23
 */

namespace app\admin\model;

use app\common\model\Base;

class Serial extends Base
{
    public function findAll($page, $rows)
    {
        $fields = "id,userid,username,(case sex when 1 then '男' when 2 then '女' else '未知' end) as sex,phone,email,serial_num,create_time";
        $offset = ($page - 1) * $rows;
        $total = $this->count();
        $data = $this->field($fields)->order('id')->select();
        $result['total'] = $total;
        $result['data'] = $data;
        return $result;
    }

    public function getSerialList()
    {
        $fields = "id,userid,username,(case sex when 1 then '男' when 2 then '女' else '未知' end) as sex,phone,email,serial_num,create_time";
        $data = $this->field($fields)->order('id')->select();
        return $data;
    }
}