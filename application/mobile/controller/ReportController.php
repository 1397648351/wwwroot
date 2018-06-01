<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 2018/6/1
 * Time: 19:54
 */

namespace app\mobile\controller;


class ReportController extends PublicController
{
    public function Index()
    {
        return $this->fetch();
    }
}