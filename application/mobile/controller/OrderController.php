<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/5/17
// +----------------------------------------------------------------------

namespace app\mobile\controller;


class OrderController extends PublicController
{
    public function index()
    {
        $goodsId = $this->request->param('id');
        if (empty($goodsId)) {
            $goodsId = 1;
        }
        $goodsModel = model('home/goods');
        $goods = $goodsModel->findById($goodsId);
        if (empty($goods)) {
            $this->redirect(url('Order/index', 'id=1'));
            exit(1);
        }
        $this->assign('goods', $goods);
        return $this->fetch();
    }
}