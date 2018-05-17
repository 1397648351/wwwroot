<?php
namespace app\mobile\controller;


class IndexController extends PublicController
{
    public function index()
    {
        $this->assign('price', 998);
        $goodsModel = model('home/goods');
        $goods = $goodsModel->fetchAll();
        $this->assign('sliderList', $goods);
        //$this->assign('price', 998);
        return $this->fetch();
    }

}
