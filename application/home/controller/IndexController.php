<?php

namespace app\home\controller;

use app\common\controller\BaseController;
use think\facade\Env;

class IndexController extends BaseController
{
    public function index()
    {
        $this->assign('price', 998);
        $this->getSlider();
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
        $this->isLogin();
        $path = Env::get('root_path') . 'public/result/' . $this->request->param('id') . '.pdf';
        header("Content-type: application/pdf");
        ob_clean();
        flush();
        readfile($path);
    }
}
