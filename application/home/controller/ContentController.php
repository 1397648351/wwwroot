<?php
/**
 * Created by PhpStorm.
 * User: rentao
 * Date: 2018/3/24
 * Time: 15:13
 */

namespace app\home\controller;

class ContentController extends BaseController
{
    public function kids()
    {
        return $this->fetch();
    }
}
