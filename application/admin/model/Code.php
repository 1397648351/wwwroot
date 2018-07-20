<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20
 * Time: 18:19
 */

namespace app\admin\model;

use app\common\model\Base;

class Code extends Base
{
    public function findPages($page, $rows)
    {
        $offset = ($page - 1) * $rows;
        $total = $this->count();
        $data = $this->order('id')->limit($offset, $rows)->select();
        $result['total'] = $total;
        $result['data'] = $data;
        return $result;
    }

    public function findAll()
    {
        $fields = "id,code,discount,(case state when 0 then '未使用' when 1 then '已使用' else '暂停使用' end) as state,create_time,update_time";
        $data = $this->field($fields)->order('id')->select();
        return $data;
    }

    public function updateRow($data)
    {
        $data['update_time'] = time();
        $this->where('id', $data['id'])->update($data);
    }

    public function addRow($data)
    {
        $data['state'] = 0;
        $data['create_time'] = time();
        $data['update_time'] = time();
        $this->insert($data, true);
    }

    public function delRow($id)
    {
        $this->where('id', $id)->delete();
    }
}