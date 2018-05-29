<?php

namespace app\admin\controller;

use app\common\controller\BaseController;
use function PHPSTORM_META\type;
use think\Exception;
use think\facade\Env;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function downExcel()
    {
        // 输出Excel表格到浏览器下载
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="order.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $goodsOrderModel = model('goodsOrder');
        $list_order = $goodsOrderModel->getOrderList();
        $sheet->setCellValueByColumnAndRow(1, 1, '订单号');
        $sheet->setCellValueByColumnAndRow(2, 1, '用户ID');
        $sheet->setCellValueByColumnAndRow(3, 1, '付款金额');
        $sheet->setCellValueByColumnAndRow(4, 1, '商品名称');
        $sheet->setCellValueByColumnAndRow(5, 1, '下单时间');
        for ($i = 0; $i < sizeof($list_order); $i++) {
            $sheet->setCellValueByColumnAndRow(1, $i + 2, $list_order[$i]['no']);
            $sheet->setCellValueByColumnAndRow(2, $i + 2, $list_order[$i]['user_id']);
            $sheet->setCellValueByColumnAndRow(3, $i + 2, $list_order[$i]['money']);
            $sheet->setCellValueByColumnAndRow(4, $i + 2, $list_order[$i]['subject']);
            $sheet->setCellValueByColumnAndRow(5, $i + 2, $list_order[$i]['create_time']);
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
