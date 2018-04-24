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

    /**
     * 返回流水
     * @param null $form
     * @return string
     * @author LiuTao liut1@kexinbao100.com
     */
    public function getMgid($form = null)
    {
        list($usec, $sec) = explode(" ", microtime());
        $usec = substr(str_replace('0.', '', $usec), 0, 4);
        $mm = date("Ym");
        if (empty($form)) {
            $str = rand_string(15, 1);
            return $mm . $usec . $str;
        } else {
            if (strlen($form) > 6) {
                $form = substr($form, 0, 6);
            }
            $form = $form . $mm;
            $str = rand_string(25 - strlen($form) - strlen($usec), 1);
            return $form . $usec . $str;
        }
    }
}