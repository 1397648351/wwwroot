<?php
namespace app\mobile\controller;

use app\common\controller\BaseController;

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
