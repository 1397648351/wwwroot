<?php
namespace app\mobile\controller;

class IndexController extends BaseController
{
    public function index()
    {
        return 'mobile';
    }

    public function test()
    {
        echo 'hello,ThinkPHP5';
    }
}
