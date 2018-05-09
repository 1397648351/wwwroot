<?php
/**
 * Created by PhpStorm.
 * User: 吴泽
 * Date: 2018/5/9
 * Time: 17:27
 */

namespace app\admin\model;

class Order extends Base
{
    public function findAll()
    {
        $fields = 'id,subject,price,body,cover';
        $this->field($fields)->find();
    }
}