<?php

namespace app\admin\controller;

use app\common\controller\BaseController;
use function PHPSTORM_META\type;
use think\Exception;
use think\facade\Env;

class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $login = false;
        $action = $this->request->action();
        if ($action != 'login' && $action != 'logout') {
            $user = array();
            if (session('?userInfo')) {
                $user = session('userInfo');
                if (isset($user['nickname']) && $this->validateLogin($user['nickname'])) {
                    $login = true;
                }
            }
            if (!$login) {
                session(null);
                $this->redirect(url('Index/login'));
            }
            $this->assign('userInfo', $user);
        }
    }

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

    public function user()
    {
        if ($this->request->isGet()) {
            return $this->fetch();
        } elseif ($this->request->isAjax()) {
            $userModel = model('user');
            $value = $this->request->param("value");
            $page = $this->request->param("page");
            $rows = $this->request->param("rows");
            $res = $userModel->findAll($page, $rows, $value);
            $this->resTableJson($res['data'], $res['total']);
        }
    }

    public function serial()
    {
        if ($this->request->isGet()) {
            return $this->fetch();
        } elseif ($this->request->isAjax()) {
            $orderModel = model('serial');
            $page = $this->request->param("page");
            $rows = $this->request->param("rows");
            $res = $orderModel->findAll($page, $rows);
            $this->resTableJson($res['data'], $res['total']);
        }
    }

    public function upload()
    {
        $datas = array();
        $type = $_POST['type'];
        try {
            $goodModel = model('goods');
            if ($type == 'add') {

            } elseif ($type == 'edit') {
                $datas['id'] = $_POST['id'];
            } else {
                $goodModel->delItem($_POST['id']);
                $this->resJson(null);
            }

            if (isset($_FILES['file_pc'])) {
                move_uploaded_file($_FILES['file_pc']['tmp_name'], Env::get('root_path') . 'public/static/dist/common/images/slider/' . $_POST['img']);
            }
            if (isset($_FILES['file_mobile'])) {
                move_uploaded_file($_FILES['file_mobile']['tmp_name'], Env::get('root_path') . 'public/static/dist/common/images/slider/mobile_' . $_POST['img']);
            }
            $datas['subject'] = $_POST['name'];
            $datas['price'] = $_POST['price'];
            $datas['cover'] = $_POST['img'];
            $datas['body'] = $_POST['body'];
            $datas['sequence'] = $_POST['sequence'];
            $goodModel->editItem($datas, $type);
            $this->resJson($datas);
        } catch (Exception $e) {
            $this->resJson($datas, '500', $e->getMessage());
        }
    }

    function uploadPdf()
    {
        $datas = array();
        $datas['id'] = $_POST['id'];
        try {
            if (isset($_FILES['file'])) {
                move_uploaded_file($_FILES['file']['tmp_name'], Env::get('root_path') . 'public/result/' . $datas['id'] . '.pdf');
                $datas['filename'] = $datas['id'] . '.pdf';
            } else {
                $this->resJson($datas, '500', '请上传正确的文件！');
            }
            $serialModel = model('serial');
            $serialModel->upload($datas);
            $this->resJson($datas);
        } catch (Exception $e) {
            $this->resJson($datas, '500', $e->getMessage());
        }
    }

    public function login()
    {
        session(null);
        if ($this->request->isGet()) {
            return $this->fetch();
        }
        $user = $_POST['user'];
        $psw = md5($_POST['psw']);
        if ($this->validateLogin($user, $psw)) {
            $this->resJson(array());
        } else {
            $this->resJson(array(), 400, '用户名或密码错误！');
        }
    }

    public function logout()
    {
        session(null);
        $this->redirect(url('Index/login'));
    }

    public function validateLogin($user = null, $psw = null, $writesession = true)
    {
        $userModel = model('user');
        $user = $userModel->getUser($user, $psw);
        if (isset($user) && $user['role'] == 1) {
            if ($writesession) session('userInfo', $user);
            return true;
        }
        return false;
    }

    public function downloadOrderExcel()
    {
        $goodsOrderModel = model('goodsOrder');
        $list_order = $goodsOrderModel->getOrderList();
        $filed = array(
            'no'             => ['title' => '订单号', 'width' => 32],
            'serial_num'     => ['title' => '序列号', 'width' => 25],
            'goods_id'       => ['title' => '商品ID', 'width' => 10],
            'subject'        => ['title' => '商品名称', 'width' => 18],
            'money'          => ['title' => '付款金额', 'width' => 10],
            'user_id'        => ['title' => '用户ID', 'width' => 10],
            'username'       => ['title' => '收件人', 'width' => 15],
            'mobile'         => ['title' => '手机号', 'width' => 15],
            'email'          => ['title' => '邮箱', 'width' => 20],
            'city'           => ['title' => '收货城市', 'width' => 15],
            'detail_address' => ['title' => '收货地址', 'width' => 30],
            'status'         => ['title' => '订单状态', 'width' => 12],
            'invoice_type'   => ['title' => '发票', 'width' => 12],
            'invoice_title'  => ['title' => '发票抬头', 'width' => 15],
            'pay_taxes_id'   => ['title' => '纳税识别号', 'width' => 20],
            'user_msg'       => ['title' => '用户留言', 'width' => 30],
            'create_time'    => ['title' => '下单时间', 'width' => 20]);
        $this->downloadExcel($filed, 'order.xlsx', 'orders', $list_order);
    }

    public function downloadUserExcel()
    {
        $value = $this->request->param("value");
        $userModel = model('user');
        $list_order = $userModel->getUserList($value);
        $filed = array(
            'id'          => ['title' => 'ID', 'width' => 8],
            'nickname'    => ['title' => '用户名', 'width' => 20],
            'sex'         => ['title' => '性别', 'width' => 10],
            'mobile'      => ['title' => '手机号', 'width' => 15],
            'email'       => ['title' => '邮箱', 'width' => 20],
            'create_time' => ['title' => '创建时间', 'width' => 20]);
        $this->downloadExcel($filed, 'user.xlsx', 'users', $list_order);
    }

    public function downloadSerialExcel()
    {
        $userModel = model('serial');
        $list_order = $userModel->getSerialList();
        $filed = array(
            'id'          => ['title' => 'ID', 'width' => 8],
            'userid'      => ['title' => '录入人ID', 'width' => 10],
            'username'    => ['title' => '用户名', 'width' => 20],
            'sex'         => ['title' => '性别', 'width' => 10],
            'phone'       => ['title' => '手机号', 'width' => 15],
            'email'       => ['title' => '邮箱', 'width' => 20],
            'serial_num'  => ['title' => '套件码', 'width' => 25],
            'create_time' => ['title' => '创建时间', 'width' => 20]);
        $this->downloadExcel($filed, 'serial.xlsx', 'serial', $list_order);
    }

    public function updatepsw()
    {
        $o_psw = $this->request->param('o_psw');
        $o_psw = md5($o_psw);
        $n_psw = $this->request->param('n_psw');
        $n_psw = md5($n_psw);
        $user = session('userInfo');
        $pass = $this->validateLogin($user['nickname'], $o_psw, false);
        if ($pass) {
            $userModel = model('user');
            $userModel->updatepsw($user['id'], $n_psw);
            $this->resJson(array());
        } else {
            $this->resJson(array(), 400, '输入的原密码错误！');
        }
    }

    public function updateOrder()
    {
        $request = $this->request;
        $data = array();
        $goods_order_id = $request->param('id');
        $data['username'] = $request->param('username');
        $data['mobile'] = $request->param('phone');
        $data['email'] = $request->param('email');
        $data['city'] = $request->param('city');
        $data['detail_address'] = $request->param('address');
        $data['invoice_title'] = $request->param('invoice');
        $data['pay_taxes_id'] = $request->param('taxes_id');
        $addressModel = model('address');
        $addressModel->updateInfo($goods_order_id, $data);
        $this->resJson(array());
    }
}
