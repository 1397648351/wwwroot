<?php
/**
 * Created by PhpStorm.
 * User: rentao
 * Date: 2018/5/7
 * Time: 16:21
 */
namespace app\mobile\controller;


use Payment\Client\Charge;
class ContentController extends PublicController
{
    public function kids_mobile()
    {
        return $this->fetch();
    }

    public function medication_mobile()
    {
        return $this->fetch();
    }

    public function skin_mobile()
    {
        return $this->fetch();
    }

    public function genetic_mobile()
    {
        return $this->fetch();
    }

    public function process_mobile()
    {
        return $this->fetch();
    }
}