<?php
/**
 * Created by PhpStorm.
 * User: rentao
 * Date: 2018/5/7
 * Time: 16:21
 */

namespace app\mobile\controller;


use Payment\Client\Charge;

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
        $this->isLogin();
        return $this->fetch();
    }

    public function binding()
    {
        $this->isLogin();
        if ($this->request->isGet()) {
            /* $id = $this->request->param('id');
            $this->assign("id", $id);
            $goodsOrderModel = model('home/goodsOrder');
            $order = $goodsOrderModel->findByOutTradeNo($id);
            $this->assign('info', $order); */
            return $this->fetch();
        } else {
            //$id = $this->request->param('id');
            $args = array();
            $args['username'] = $this->request->param('name');
            $args['phone'] = $this->request->param('phone');
            $args['email'] = $this->request->param('email');
            $args['express'] = $this->request->param('express');
            $args['sex'] = $this->request->param('sex');
            $args['serial_num'] = $this->request->param('number');
            $serialModel = model('home/serial');
            $result = $serialModel->insertSerialNum($args);
            $this->resJson(array(), $result['code'], $result['msg']);
        }
    }

    public function bindingend()
    {
        $this->isLogin();
        return $this->fetch();
    }

}