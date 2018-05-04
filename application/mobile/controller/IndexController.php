<?php
namespace app\mobile\controller;


use Payment\Client\Charge;

class IndexController extends PublicController
{
    public function index()
    {
        $this->getJsSign();
        $this->assign('price', 998);
        return $this->fetch();
    }

    public function test()
    {
        echo 'hello,ThinkPHP5';
    }

    public function wxPay()
    {
        $req = $this->request;
        $outTradeNo = $this->getMgid().'_wx';
        $goodsId = $req->param('goods_id');
        $goodsModel = model('goods');
        $goods = $goodsModel->find($goodsId);
        $config = $this->wxConfigData();
        $type = 'wx_pub';
        $params['config'] = $this->wxConfigData();
        $payParam = $this->setWxPayParam($outTradeNo,$goods);
        $res = Charge::run($type, $config, $payParam);
        dump($res);
    }

    /**
     * @return array
     * @author LiuTao liut1@kexinbao100.com
     */
    private function setWxPayParam($outTradeNo,$goods)
    {
        $payParam = array();
        $payParam['body'] = $goods['body'];
        $payParam['subject'] = $goods['subject'];
        $payParam['order_no'] = $outTradeNo;
        //单位 元
        $payParam['amount'] = '0.01';
        //用户客户端实际IP地址
        $payParam['client_ip'] = '127.0.0.1';
        $payParam['timeout_express'] = 3600 + time();
        //异步通知原样返回数据
        $payParam['return_param'] = 'pica';
        $payParam['product_id'] = $goods['id'];
        $payParam['openid'] = session('openid');
        return $payParam;
    }
}
