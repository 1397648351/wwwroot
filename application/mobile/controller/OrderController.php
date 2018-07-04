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

use Payment\Client\Charge;
use Payment\Common\PayException;

class OrderController extends PublicController
{
    public function index()
    {
        $this->isLogin();
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

    public function orderList()
    {
        $this->isLogin();
        //$userModel = model('home/user');
        //$user = $userModel->findByOpenid(session('openid'));
        $goodsOrderModel = model('home/goodsOrder');
        $data = $goodsOrderModel->findOrderByUser(session('userInfo')['id'], true);
        $this->assign('list', $data);
        return $this->fetch();
    }

    public function order()
    {
        $this->isLogin();
        if (!$this->request->isPost()) {
            $this->resJson(array(), 2001, '需要post提交');
        }
        $req = $this->request;
        $outTradeNo = $this->getMgid() . '_wx';
        $goodsId = $req->param('goods_id');
        $username = $this->checkEmpty($req->param('username'), '收件人姓名不能为空！');
        $mobile = $this->checkEmpty($req->param('mobile'), '手机号不问为空！');
        $email = $req->param('email');
        $city = $this->checkEmpty($req->param('city'), '所在地区不能为空！');
        $detailAdd = $this->checkEmpty($req->param('detail_address'), '详细地址不能为空！');
        $num = $req->param('num');
        $goodsModel = model('home/goods');
        $goods = $goodsModel->find($goodsId);
        if (empty($goods)) {
            $this->resJson(array(), 2001, '商品不存在');
        }
        $openid = session('openid');
        if(empty($openid)){
            $type = 'wx_wap';
            $user = session('userInfo');
        } else {
            $type = 'wx_pub';
            $openid = session('openid');
            $userModel = model('home/user');
            $user = $userModel->findByOpenid($openid);
        }
        $config = $this->wxConfigData();
        $payParam = $this->setWxPayParam($outTradeNo, $goods, $num);
        try {
            $res = Charge::run($type, $config, $payParam);
            $goodsOrderModel = model('home/goodsOrder');
            $goodsOrderId = $goodsOrderModel->addInfo($goods, $user['id'], $outTradeNo, 'wx', $num);
            $addressId = $this->setAddress($goodsOrderId);
            $data = array();
            $data['order_id'] = $goodsOrderId;
            $data['package'] = $res;
            $this->resJson($data, 200, '下单成功');
        } catch (PayException $e) {
            \think\facade\Log::log($e->errorMessage(), '-----------------');
            $this->resJson($e->errorMessage(), 1000, '下单异常');
        }
    }

    /**
     * @param $goodsOrderId 订单编号
     * @return mixed
     * @author LiuTao liut1@kexinbao100.com
     */
    private function setAddress($goodsOrderId)
    {
        $req = $this->request;
        $username = $req->param('username');
        $mobile = $req->param('mobile');
        $email = $req->param('email');
        $city = $req->param('city');
        $detailAdd = $req->param('detail_address');
        //发票类型  0：无  1：个人  2：公司
        $invoiceType = $req->param('invoice_type');
        //抬头
        $invoiceTitle = $req->param('invoice_title');
        //税号
        $payTaxesId = $req->param('pay_taxes_id');
        //留言
        $userMsg = $req->param('user_msg');
        $addressModel = model('home/address');
        $addressId = $addressModel->addInfo($username, $mobile, $email, $city, $detailAdd, $goodsOrderId, $invoiceType, $invoiceTitle, $payTaxesId, $userMsg);
        return $addressId;
    }

    /**
     * @return array
     * @author LiuTao liut1@kexinbao100.com
     */
    private function setWxPayParam($outTradeNo, $goods, $num)
    {
        $payParam = array();
        $payParam['body'] = $goods['body'];
        $payParam['subject'] = $goods['subject'];
        $payParam['order_no'] = $outTradeNo;
        //单位 元
        $payParam['amount'] = $goods['price'] * $num;
        //用户客户端实际IP地址
        $payParam['client_ip'] = $this->request->ip();
        $payParam['timeout_express'] = 3600 + time();
        //异步通知原样返回数据
        $payParam['return_param'] = 'pica';
        $payParam['product_id'] = $goods['id'];
        $payParam['openid'] = session('openid');
        if(empty(session('openid'))){
            $payParam['wap'] = 'Wap';
            $payParam['wap_url'] = 'http://www.picagene.com';
            $payParam['wap_name'] = '基因检测';
        }
        return $payParam;
    }
}