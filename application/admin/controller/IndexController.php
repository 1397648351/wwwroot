<?php

namespace app\admin\controller;

use app\common\controller\BaseController;

class IndexController extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }

    public function order()
    {
        if ($this->request->isGet()) {
            return $this->fetch();
        } elseif ($this->request->isAjax()) {

        }
    }
}
