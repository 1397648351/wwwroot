<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 2018/6/15
 * Time: 16:14
 */

namespace app\home\model;

use app\common\model\Base;

class Serial extends Base
{
    public function insertSerialNum($args)
    {
        $result = array();
        $map = array();
        $map['serial_num'] = $args['serial_num'];
        $count = $this->where($map)->count();
        if ($count > 0) {
            $result['code'] = 1001;
            $result['msg'] = '该唾液采集编码已被绑定！';
            return $result;
        }
        $args['create_time'] = time();
        $this->insert($args);
        $result['code'] = 200;
        $result['msg'] = '';
        return $result;
    }

    public function findReport($userid)
    {
        $map = array();
        $map['userid'] = $userid;
        $data = $this->where($map)->select();
        return $data;
    }
}