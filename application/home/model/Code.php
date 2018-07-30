<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/7/30
// +----------------------------------------------------------------------

namespace app\home\model;


use app\common\model\Base;

class Code extends Base
{
    public function findByCode($code)
    {
        $map = array();
        $map['code'] = $code;
        $fields = 'id,code,discount';
        return $this->findByWhere($map, $fields);
    }
}