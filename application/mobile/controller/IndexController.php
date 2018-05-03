<?php
namespace app\mobile\controller;


class IndexController extends PublicController
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
