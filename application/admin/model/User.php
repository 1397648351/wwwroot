<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 2018/5/10
 * Time: 23:35
 */

namespace app\admin\model;

use app\common\model\Base;

class User extends Base
{
    public function getUser($user, $psw)
    {
        $map = array();
        $map['nickname'] = $user;
        $map['role'] = 1;
        $fields = 'id,nickname,role';
        if (isset($psw) && !empty($psw)) {
            $map['password'] = $psw;
        }
        return $this->field($fields)->where($map)->find();
    }
}