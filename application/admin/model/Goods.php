<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 2018/5/10
 * Time: 23:35
 */

namespace app\admin\model;

use app\common\model\Base;

class Goods extends Base
{
    public function findAll($page, $rows)
    {

        $fields = 'id,subject,price,body,cover,create_time,update_time';
        $offset = ($page - 1) * $rows;
        $total = $this->count();
        $data = $this->field($fields)->order('id')->limit($offset, $rows)->select();
        $result['total'] = $total;
        $result['data'] = $data;
        return $result;
    }

    public function editItem($data, $type)
    {
        if ($type == 'add') $data['create_time'] = time();
        $data['update_time'] = time();
        if ($type == 'add') {
            $this->insert($data, true);
        } else {
            $this->where('id', $data['id'])->data($data)->update();;
        }
    }

    public function delItem($id)
    {
        $this->where('id', $id)->delete();
    }
}