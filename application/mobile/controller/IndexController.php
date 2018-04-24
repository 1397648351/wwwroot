<?php
namespace app\mobile\controller;

use app\common\controller\BaseController;

class IndexController extends BaseController
{
    public function index()
    {
        $this->assign('price', 998);
        return $this->fetch();
    }

    public function test()
    {
        echo 'hello,ThinkPHP5';
    }
}
