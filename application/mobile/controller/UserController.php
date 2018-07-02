<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 2018/7/2
 * Time: 10:11
 */

namespace app\mobile\controller;


class UserController extends PublicController
{
    public function login()
    {
        return $this->fetch();
    }
}