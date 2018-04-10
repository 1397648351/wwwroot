<?php

namespace app\common\controller;

use think\Controller;

class BaseController extends Controller {
	function initialize() {
		//echo 'aa<br />';
	}

    public function checkMobile($mobile)
    {
        $this->checkEmpty($mobile, 'mobile不能为空');
        if(!preg_match('/^1[0-9]{10}$/', $mobile)){
            $this->resJson($mobile,1011, '手机号格式错误');
        }
        return $mobile;
    }

    /**
     * 非空验证
     * @param $data
     * @param string $describe
     * @param $type
     * @return mixed
     * @author LiuTao liut1@kexinbao100.com
     */
    public function checkEmpty($data, $describe = '参数不能为空')
    {
        if(empty($data)){
            $this->resJson(array(), 1002, $describe);
        }
        return $data;
    }

    /**
     * json返回
     * @param $data 数据
     * @param int $status 状态码
     * @param int $msg 描述
     * @author LiuTao liut1@kexinbao100.com
     */
    public function resJson($data = array(), $status_code = 1001, $msg = 0)
    {
        header('Content-Type:application/json; charset=utf-8');
        $res['data'] = $data;
        $res['status_code'] = $status_code;
        $res['msg'] = $msg;
        $str = json_encode($res, JSON_UNESCAPED_UNICODE);
        exit($str);
    }
}