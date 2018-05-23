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

    public function validateLogin($user = null, $psw = null)
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
