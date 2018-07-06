<?php
/**
 * Created by PhpStorm.
 * User: rentao
 * Date: 2018/3/24
 * Time: 15:13
 */

namespace app\home\controller;

use app\common\controller\BaseController;

class ContentController extends BaseController
{
    public function kids()
    {
        $this->getSlider();
        return $this->fetch();
    }

    public function skin()
    {
        $this->getSlider();
        return $this->fetch();
    }

    public function inheritance()
    {
        $this->getSlider();
        return $this->fetch();
    }

    public function medication()
    {
        $this->getSlider();
        return $this->fetch();
    }

    public function process()
    {
        $this->getSlider();
        return $this->fetch();
    }

    public function selreport()
    {
        $this->isLogin();
        $this->getSlider();
        $userinfo = session('userInfo');
        $serialModel = model('serial');
        $data = $serialModel->findReport($userinfo['id'], true);
        $this->assign('list', $data);
        return $this->fetch();
    }

}
