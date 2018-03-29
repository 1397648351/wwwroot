<?php
/**
 * Created by PhpStorm.
 * User: rentao
 * Date: 2018/3/24
 * Time: 15:13
 */

namespace app\home\controller;

class ContentController extends BaseController
{
    public function kids()
    {
        return $this->fetch();
    }

    public  function  skin()
    {
        return $this->fetch();
    }

    public  function  inheritance()
    {
        return $this->fetch();
    }

    public  function  medication()
    {
        return $this->fetch();
    }

    public  function  process()
    {
        return $this->fetch();
    }
}
