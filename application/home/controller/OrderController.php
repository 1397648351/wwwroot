<?php
// +----------------------------------------------------------------------
// | PHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.kexin.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liutao <liut1@kexinbao100.com>
// | Date  : 2018/3/25
// +----------------------------------------------------------------------

namespace app\home\controller;

use app\home\model\Code;
use houdunwang\qrcode\QrCode;
use Payment\Client\Charge;
use Payment\Client\Query;
use Payment\Common\PayException;
use app\common\controller\BaseController;

/**
 * 下单支付处理
 * Class OrderController
 * @package app\home\controller
 */
class OrderController extends BaseController
{
    public function order()
    {
        $this->isLogin();
        $this->getSlider();
        $id = $this->request->param('id');
        if (empty($id)) {
            $id = 1;
        }
        $goodsModel = model('goods');
        $goods = $goodsModel->findById($id);
        if (empty($goods)) {
            $this->redirect(url('Order/order', 'id=1'));
            exit(1);
        }
        $this->assign('goods', $goods);
        if (session('?userInfo')) {
            $this->assign("username", session('userInfo')['nickname']);
        }
        return $this->fetch();
    }

    public function goodsOrder()
    {
        $req = $this->request;
        $payType = $req->param('pay_type');
        $goodsId = $req->param('goods_id');
        $username = $this->checkEmpty($req->param('username'), '收件人姓名不能为空！');
        $mobile = $this->checkEmpty($req->param('mobile'), '手机号不问为空！');
        $email = $req->param('email');
        $city = $this->checkEmpty($req->param('city'), '所在地区不能为空！');
        $detailAdd = $this->checkEmpty($req->param('detail_address'), '详细地址不能为空！');
        $code = $req->param('code');
        if(empty($code)){
            $discount = 1;
        } else {
            $codeModel = new Code();
            $info = $codeModel->findByCode($code);
            if(empty($info) || $info['state'] == 0){
                $this->resJson(array(),2001, '折扣编码不存在');
            }
            $discount = $info['discount']/10;
        }
        $num = $req->param('num');
        $goodsModel = model('goods');
        $goods = $goodsModel->find($goodsId);
        if (empty($goods)) {
            $this->resJson(array(), 2001, '商品不存在');
        }
        $payParams = $this->setParam($payType, $goods, $num, $discount);
        try {
            $res = Charge::run($payParams['type'], $payParams['config'], $payParams['pay_param']);
            if ($res['return_msg'] == 'OK') {
                $goodsOrderModel = model('goodsOrder');
                $user = session('userInfo');
                $goodsOrderId = $goodsOrderModel->addInfo($goods, $user['id'], $payParams['out_trade_no'], $payType, $num);
                $code_url = $res['code_url'];
                $qrName = $goodsOrderId . '.png';
                //生成支付二维码
                $this->setQr($code_url, $qrName);
                //保存收货地址和发票相关内容
                $res = $this->setAddress($goodsOrderId);
                if ($res) {
                    $data = array();
                    $data['order_id'] = $goodsOrderId;
                    $this->resJson($data, 200, '下单成功');
                } else {
                    $this->resJson($res, 2002, '地址保存失败');
                }
            } else {
                $this->resJson($res, 2001, '下单失败');
            }
        } catch (PayException $e) {
            echo $e->errorMessage();
            exit;
        }
    }

    public function orderList()
    {
        $this->isLogin();
        $this->getSlider();
        $goodsOrderModel = model('goodsOrder');
        $data = $goodsOrderModel->findOrderByUser(session('userInfo')['id']);
        $this->assign('list', $data);
        return $this->fetch();
    }

    public function pay()
    {
        $orderId = $this->request->param('order_id');
        $goodsOrderModel = model('goodsOrder');
        $order = $goodsOrderModel->find($orderId);
        $this->assign('pay_img', $orderId . '.png');
        $this->assign('order', $order);
        return $this->fetch();
    }

    public function queryPay()
    {
        try {
            $data = array();
            $data['out_trade_no'] = $this->request->param('out_trade_no');
            $config = $this->wxConfigData();
            $ret = Query::run('wx_charge', $config, $data);
            $trade_state = $ret['trade_state'];
            switch ($trade_state) {
                case 'SUCCESS':
                    $this->resJson($trade_state, 200, '支付成功');
                    break;
                case 'NOPAY':
                    $this->resJson($trade_state, 100, '未支付');
                    break;
                case 'PAYERROR':
                    $this->resJson($trade_state, 1001, '支付失败');
                    break;
                case 'CLOSED':
                    $this->resJson($trade_state, 100, '已关闭');
                    break;
                default :
                    $this->resJson($trade_state, 100, '未支付');
                    break;
            }
        } catch (PayException $e) {
            echo $e->errorMessage();
            exit;
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
        $addressModel = model('address');
        $addressId = $addressModel->addInfo($username, $mobile, $email, $city, $detailAdd, $goodsOrderId, $invoiceType, $invoiceTitle, $payTaxesId, $userMsg);
        return $addressId;
    }

    /**
     * 设置支付二维码
     * @param $codeUrl
     * @param $goodsOrderId
     * @author LiuTao liut1@kexinbao100.com
     */
    private function setQr($codeUrl, $qrName)
    {
        $s = QrCode::width(500)//高度
        ->height(500)//背景颜色
        ->backColor(5, 10, 0)//前景颜色
        ->foreColor(55, 255, 110)->save($codeUrl, 'qr/' . $qrName);
    }

    /**
     * 支付参数设置
     * @param $payType
     * @return array
     * @author LiuTao liut1@kexinbao100.com
     */
    private function setParam($payType, $goods, $num, $discount)
    {
        $params = array();
        $outTradeNo = $this->getMgid() . '_' . $payType;
        switch ($payType) {
            case 'ali':
                $params['type'] = 'ali_qr';
                $params['config'] = $this->aliConfigData();
                $params['pay_param'] = $this->setAliPayParam($outTradeNo, $goods, $num, $discount);
                break;
            case 'wx':
                $params['type'] = 'wx_qr';
                $params['config'] = $this->wxConfigData();
                $params['pay_param'] = $this->setWxPayParam($outTradeNo, $goods, $num, $discount);
                break;
            default:
                break;
        }
        if (empty($params)) {
            $this->resJson(array(), 2001, '支付类型不存在');
        }
        $params['out_trade_no'] = $outTradeNo;
        return $params;
    }

    /**
     * 支付宝支付公共参数
     * @param $param
     * @author LiuTao liut1@kexinbao100.com
     */
    private function setAliPayParam($outTradeNo, $goods, $num, $discount)
    {
        $payParam = array();
        //商品具体描述
        $payParam['body'] = $goods['body'];
        //商品标题
        $payParam['subject'] = $goods['subject'];
        //订单号
        $payParam['order_no'] = $outTradeNo;
        //需要支付金额 元
        $payParam['amount'] = $goods['price'] * $num * $discount;
        //过期时间（当前时间+过期s数） 时间戳
        $payParam['timeout_express'] = 3600 + time();
        $payParam['return_param'] = 'pica';
        //商品类型  0：虚拟 1：实物  否
        $payParam['goods_type'] = '0';
        //门店标记  否
        $payParam['store_id'] = '';
        return $payParam;
    }

    /**
     * @return array
     * @author LiuTao liut1@kexinbao100.com
     */
    private function setWxPayParam($outTradeNo, $goods, $num, $discount)
    {
        $payParam = array();
        $payParam['body'] = $goods['body'];
        $payParam['subject'] = $goods['subject'];
        $payParam['order_no'] = $outTradeNo;
        //单位 元
        $payParam['amount'] = $goods['price'] * $num * $discount;
        //用户客户端实际IP地址
        $payParam['client_ip'] = $this->request->ip();//'127.0.0.1';
        $payParam['timeout_express'] = 3600 + time();
        //异步通知原样返回数据
        $payParam['return_param'] = 'pica';
        $payParam['product_id'] = $goods['id'];
        $payParam['openid'] = '';
        return $payParam;
    }
}