<?php

namespace app\home\controller;

use app\common\controller\BaseController;
use think\facade\Env;

class IndexController extends BaseController
{
    public function index()
    {
        $this->assign('price', 998);
        $goodsModel = model('goods');
        $goods = $goodsModel->fetchAll();
        $this->assign('sliderList', $goods);
        return $this->fetch();
    }

    public function agreement()
    {
        return $this->fetch();
    }

    public function agreement1()
    {
        return $this->fetch();
    }

    public function test()
    {
        //echo Env::get('app_path');
        echo md5('Picagene789');
        //echo Env::get('root_path');
//        $req = $this->request->ip();
//        echo $req;
    }

    public function resultfile()
    {
        $path = Env::get('root_path') . 'public/result/' . $_GET['id'] . '.pdf';
        header("Content-type: application/pdf");
        ob_clean();
        flush();
        readfile($path);
    }
}
