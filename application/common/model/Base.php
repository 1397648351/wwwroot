<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/3/22
// +----------------------------------------------------------------------

namespace app\common\model;


use think\Model;

class Base extends Model
{
    /**
     * @param $map
     * @param string $fields
     * @return array|null|\PDOStatement|string|Model
     * @author LiuTao liut1@kexinbao100.com
     */
    public function findByWhere($map, $fields='*')
    {
        return $this->field($fields)->where($map)->find();
    }

    /**
     * @param $map
     * @param string $fields
     * @param string $order
     * @return array|\PDOStatement|string|\think\Collection
     * @author LiuTao liut1@kexinbao100.com
     */
    public function selectByWhere($map, $fields='*', $order='')
    {
        if (empty($order)) {
            return $this->field($fields)->where($map)->select();
        } else {
            return $this->field($fields)->where($map)->order($order)->select();
        }
    }

    /**
     * @param $data
     * @param $map
     * @return false|int
     * @author LiuTao liut1@kexinbao100.com
     */
    public function updateDataByMap($data, $map)
    {
        return $this->save($data, $map);
    }

    /**
     * @param $map
     * @return int|string
     * @author LiuTao liut1@kexinbao100.com
     */
    public function findCountByWhere($map)
    {
        return $this->where($map)->count();
    }
}