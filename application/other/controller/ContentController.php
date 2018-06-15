<?php
/**
 * Created by PhpStorm.
 * User: rentao
 * Date: 2018/5/7
 * Time: 16:21
 */

namespace app\other\controller;



class ContentController extends PublicController
{
    public function kids_mobile()
    {
        return $this->fetch();
    }

    public function medication_mobile()
    {
        return $this->fetch();
    }

    public function skin_mobile()
    {
        return $this->fetch();
    }

    public function genetic_mobile()
    {
        return $this->fetch();
    }

    public function process_mobile()
    {
        return $this->fetch();
    }

    public function seeorder()
    {
        $orderId = $this->request->param('order_id');
        $goodsOrderModel = model('home/goodsOrder');
        $order = $goodsOrderModel->findOrderInfo($orderId);
        $this->assign('info', $order[0]);
        return $this->fetch();
    }

    public function selectorder()
    {
        return $this->fetch();
    }

    public function binding()
    {
        if ($this->request->isGet()) {

            $id = $this->request->param('id');
            $this->assign("id", $id);
            $goodsOrderModel = model('home/goodsOrder');
            $order = $goodsOrderModel->findByOutTradeNo($id);
            $this->assign('info', $order);
            return $this->fetch();
        } else {
            $id = $this->request->param('id');
            $num = $this->request->param('num');
            $goodsOrderModel = model('home/goodsOrder');
            $result = $goodsOrderModel->insertSerialNum($id, $num);
            $this->resJson(array(), $result['code'], $result['msg']);
        }
    }

    public function bindingend()
    {
        return $this->fetch();
    }

}