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

    public function findAll($page, $rows)
    {
        $fields = "id,nickname,(case sex when 1 then '男' when 2 then '女' else '未知' end) as sex,mobile,email,create_time";
        $offset = ($page - 1) * $rows;
        $total = $this->count();
        $data = $this->field($fields)->order('id')->limit($offset, $rows)->select();
        $result['total'] = $total;
        $result['data'] = $data;
        return $result;
    }

    public function getUserList()
    {
        $fields = "id,nickname,(case sex when 1 then '男' when 2 then '女' else '未知' end) as sex,mobile,email,create_time";
        $data = $this->field($fields)->order('id')->select();
        return $data;
    }
}