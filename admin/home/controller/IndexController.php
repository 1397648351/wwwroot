<?php
namespace admin\home\controller;

class IndexController extends BaseController
{
	public function index()
	{
		echo Container::get ( 'app' );
		return "123232";
	}

	public function test()
	{
		echo 'hello,ThinkPHP5';
	}
}
