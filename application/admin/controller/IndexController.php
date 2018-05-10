<?php

namespace app\admin\controller;

use app\common\controller\BaseController;
use app\admin\model\GoodsOrder;

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
            $orderModel = model('goodsOrder');
            $page = $this->request->param("page");
            $rows = $this->request->param("rows");
            $res = $orderModel->findAll($page, $rows);
            $this->resTableJson($res['data'], $res['total']);
        }
    }

    public function goods()
    {
        if ($this->request->isGet()) {
            return $this->fetch();
        } elseif ($this->request->isAjax()) {
            $orderModel = model('goods');
            $page = $this->request->param("page");
            $rows = $this->request->param("rows");
            $res = $orderModel->findAll($page, $rows);
            $this->resTableJson($res['data'], $res['total']);
        }
    }
}
