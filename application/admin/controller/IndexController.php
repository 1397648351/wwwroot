<?php

namespace app\admin\controller;

use app\common\controller\BaseController;
use think\Exception;
use think\facade\Env;

class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $u = array();
        $u['nickname'] = 'liutao';
        session('userInfo', $u);
        $login = false;
        $action = $this->request->action();
        if ($action != 'login') {
            if (session('?userInfo')) {
                $user = session('userInfo');
                if ($this->validateLogin($user['nickname'])) {
                    $login = true;
                }
            }
            if (!$login) {
                session(null);
                $this->redirect(url('Index/login'));
            }
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

            if (isset($_FILES['file'])) {
                move_uploaded_file($_FILES['file']['tmp_name'], Env::get('root_path') . 'public/static/dist/common/images/slider/' . $_POST['img']);
            }
            $datas['subject'] = $_POST['name'];
            $datas['price'] = $_POST['price'];
            $datas['cover'] = $_POST['img'];
            $datas['body'] = $_POST['body'];
            $goodModel->editItem($datas, $type);
            $this->resJson($datas);
        } catch (Exception $e) {
            $this->resJson($datas, '500', $e->getMessage());
        }
    }

    public function login()
    {
        if ($this->request->isGet()) return $this->fetch();
    }

    public function validateLogin($user, $psw = null)
    {
        $userModel = model('user');
        $user = $userModel->getUser($user, $psw);
        if (isset($user) && $user['role'] == 1) {
            session('userInfo', $user);
            return true;
        }
        return false;
    }
}
