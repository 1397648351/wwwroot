<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 2018/6/1
 * Time: 19:54
 */

namespace app\mobile\controller;


class ReportController extends PublicController
{
    public function index()
    {
        return $this->fetch();
    }

    public function detail()
    {
        $data = array();
        $type = $this->request->param('type');
        if (!isset($type)) $type = 6;
        switch ($type) {
            case 0:
                $data['title'] = '祖源分析';
                $data['list'] = array(
                    ['name' => '肺癌', 'value' => '平均', 'icon' => 'normal'],
                );
                break;
            case 1:
                $data['title'] = '营养补充';
                $data['list'] = array(
                    ['name' => '肺癌', 'value' => '平均', 'icon' => 'normal'],
                );
                break;
            case 2:
                $data['title'] = '遗传特征';
                $data['list'] = array(
                    ['name' => '肺癌', 'value' => '平均', 'icon' => 'normal'],
                );
                break;
            case 3:
                $data['title'] = '遗传风险';
                $data['list'] = array(
                    ['name' => '肺癌', 'value' => '平均', 'icon' => 'normal'],
                );
                break;
            case 4:
                $data['title'] = '用药指南';
                $data['list'] = array(
                    ['name' => '肺癌', 'value' => '平均', 'icon' => 'normal'],
                );
                break;
            case 5:
                $data['title'] = '健康风险';
                $data['list'] = array(
                    ['name' => '肺癌', 'value' => '平均', 'icon' => 'normal'],
                );
                break;
            case 6:
            default:
                $data['total'] = 16;
                $data['title'] = '肿瘤检测';
                $data['list'] = array(
                    ['name' => '肺癌', 'value' => '平均', 'icon' => 'normal'],
                    ['name' => '胃癌', 'value' => '关注', 'icon' => 'alert'],
                    ['name' => '肝癌', 'value' => '关注', 'icon' => 'alert'],
                    ['name' => '道癌', 'value' => '密切关注', 'icon' => 'error'],
                    ['name' => '肠癌', 'value' => '平均', 'icon' => 'normal'],
                    ['name' => '咽癌', 'value' => '密切关注', 'icon' => 'error'],
                    ['name' => '甲状腺癌', 'value' => '平均', 'icon' => 'normal'],
                    ['name' => '膀胱癌', 'value' => '平均', 'icon' => 'normal'],
                    ['name' => '淋巴癌', 'value' => '平均', 'icon' => 'normal'],
                );
                break;
        }
        $this->assign('data', $data);
        return $this->fetch();
    }
}