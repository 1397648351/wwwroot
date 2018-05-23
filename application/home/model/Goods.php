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

class Goods extends Base
{
    public function findById($id)
    {
        $map = array();
        $map['id'] = $id;
        $fields = 'id,subject,price,body,cover';
        return $this->findByWhere($map, $fields);
    }

    public function fetchAll()
    {
        $fields = 'id,subject,price,body,cover';
        return $this->field($fields)->order('sequence')->select();
    }
}