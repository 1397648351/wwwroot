<?php

namespace app\mobile\controller;

use think\facade\Env;


class IndexController extends PublicController
{
    public function index()
    {
        $this->assign('price', 998);
        $goodsModel = model('home/goods');
        $goods = $goodsModel->fetchAll();
        $this->assign('sliderList', $goods);
        //$this->assign('price', 998);
        return $this->fetch();
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
