<?php
namespace app\home\controller;

class IndexController extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }

    public function test()
    {
        echo 'hello,ThinkPHP5';
    }
}
