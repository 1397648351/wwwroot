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
        $sexnum = $this->request->param('sex');
        if (!isset($type)) $type = 6;
        //sex参数为0当前为男性，1为女性
        $data['sex'] = !isset($sexnum) ? '男' : $data['sex'] = $sexnum == 0 ? '男' : '女';
        $data['id'] = $type;
        switch ($type) {
            case 0:
                //祖源相似性 姓氏溯源 家族基因
                $data['title'] = '祖源分析';
                $data['list'] = array(
                    ['name' => '祖源成分', 'value' => '60.1% 南方汉族', 'icon' => 'none'],
                    ['name' => '父系单倍群', 'value' => 'C2e1a2e1a1c', 'icon' => 'none'],
                    ['name' => '母系单倍群', 'value' => 'M10a1c1', 'icon' => 'none'],
                    ['name' => '尼安德特人比例', 'value' => '2.993%', 'icon' => 'none'],
                    ['name' => '祖源相似性', 'value' => '', 'icon' => 'none'],
                    ['name' => '姓氏溯源', 'value' => '', 'icon' => 'none'],
                    ['name' => '家族基因', 'value' => '', 'icon' => 'none'],
                );
                break;
            case 1:
                $data['title'] = '营养补充';
                $data['list'] = array(
                    ['name' => '维生素E营养需求', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '铁营养需求', 'value' => '稍高', 'icon' => 'alert'],
                    ['name' => '维生素B12营养需求', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '维生素A营养需求', 'value' => '高', 'icon' => 'error'],
                    ['name' => '叶酸营养需求', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '钙营养需求', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '酒精代谢能力', 'value' => '弱', 'icon' => 'error'],
                    ['name' => '糖乳代谢能力', 'value' => '弱', 'icon' => 'error'],
                    ['name' => '咖啡因代谢能力', 'value' => '正常', 'icon' => 'normal']
                );
                break;
            case 2:
                $data['title'] = '遗传特征';
                $data['list'] = array(
                    ['name' => '酒精性脸红', 'value' => '酒精不脸红', 'icon' => 'none'],
                    ['name' => '苦味敏感度', 'value' => '对苦味敏感', 'icon' => 'none'],
                    ['name' => '耳垢类型', 'value' => '干燥', 'icon' => 'none'],
                    ['name' => '吸烟倾向', 'value' => '不易上瘾', 'icon' => 'none'],
                    ['name' => '血糖水平', 'value' => '空腹血糖高', 'icon' => 'none'],
                    ['name' => '见光喷嚏', 'value' => '几率高', 'icon' => 'none'],
                    ['name' => '避错倾向', 'value' => '善于避免错误', 'icon' => 'none'],
                    ['name' => '绝对音准', 'value' => '能力明显', 'icon' => 'none'],
                    ['name' => 'ABO血型', 'value' => 'B血型', 'icon' => 'none'],
                    ['name' => 'APOE分型', 'value' => 'APOE基因型为ε3/ε3', 'icon' => 'none'],
                    ['name' => '深度睡眠', 'value' => '深度睡眠时间较短', 'icon' => 'none'],
                    ['name' => '基因身高', 'value' => '176.8 CM', 'icon' => 'none']
                );
                break;
            case 3:
                $data['title'] = '遗传风险';
                $data['list'] = array(
                    ['name' => '家族性地中海热', 'value' => '携带 1 个风险突变', 'icon' => 'error'],
                    ['name' => '精氨酰琥珀酸尿症', 'value' => '携带 1 个风险突变', 'icon' => 'error'],
                    ['name' => 'α-1抗胰蛋白酶缺乏', 'value' => '未携带', 'icon' => 'normal'],
                    ['name' => '先天性1A型糖基化病', 'value' => '未携带', 'icon' => 'normal'],
                    ['name' => '遗传性耳聋', 'value' => '未携带', 'icon' => 'normal'],
                    ['name' => '囊性纤维化', 'value' => '未携带', 'icon' => 'normal'],
                    ['name' => '二氢嘧啶脱氢酶缺乏症', 'value' => '未携带', 'icon' => 'normal'],
                    ['name' => '凝血因子XI缺陷症', 'value' => '未携带', 'icon' => 'normal'],
                    ['name' => '家族性自主神经功能异常', 'value' => '未携带', 'icon' => 'normal'],
                    ['name' => 'B型家族性高胆固醇血症', 'value' => '未携带', 'icon' => 'normal'],
                    ['name' => '胼胝体伴周围神经发育不全', 'value' => '未携带', 'icon' => 'normal'],
                    ['name' => '范可尼贫血', 'value' => '未携带', 'icon' => 'normal']
                );
                break;
            case 4:
                $data['title'] = '用药指南';
                $data['list'] = array(
                    ['name' => '硝酸甘油', 'value' => '增加/替换', 'icon' => 'normal'],
                    ['name' => '聚乙二醇化干扰素联合利巴韦林', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '华法林-CYP2C9', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '塞来昔布', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '氯沙坦', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '他莫昔芬', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '阿米替林-CYP2D6', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '乙醇', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '华法林-CYP4F2', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '他汀类药物', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '伊立替康', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '美托洛尔', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '第二代抗精神病药', 'value' => '正常', 'icon' => 'normal'],
                    ['name' => '他克莫司', 'value' => '正常', 'icon' => 'normal']
                );
                break;
            case 5:
                $data['title'] = '健康风险';
                $data['list'] = array(
                    ['name' => '精神分裂症', 'value' => '11.68 倍', 'icon' => 'error'],
                    ['name' => '类风湿关节炎', 'value' => '4.56 倍', 'icon' => 'error'],
                    ['name' => '子宫内膜异位', 'value' => '3.53 倍', 'icon' => 'error'],
                    ['name' => '甲状腺癌', 'value' => '3.13 倍', 'icon' => 'error'],
                    ['name' => '胰腺癌', 'value' => '2.08 倍', 'icon' => 'error'],
                    ['name' => '强迫症', 'value' => '1.94 倍', 'icon' => 'error'],
                    ['name' => '骨髓增生性肿瘤', 'value' => '1.75 倍', 'icon' => 'error'],
                    ['name' => '多发性硬化', 'value' => '1.71 倍', 'icon' => 'error'],
                    ['name' => '特发性肺纤维化', 'value' => '1.59 倍', 'icon' => 'error'],
                    ['name' => '心房纤维性颤动', 'value' => '1.58 倍', 'icon' => 'error'],
                    ['name' => '妊娠剧吐', 'value' => '1.57 倍', 'icon' => 'error'],
                    ['name' => '先兆子痫', 'value' => '1.57 倍', 'icon' => 'error'],
                    ['name' => '脊柱侧凸', 'value' => '1.57 倍', 'icon' => 'error'],
                    ['name' => '肾癌', 'value' => '1.55 倍', 'icon' => 'error'],
                    ['name' => '发作性嗜睡病', 'value' => '1.48 倍', 'icon' => 'error'],
                    ['name' => '阿兹海默病', 'value' => '1.37 倍', 'icon' => 'error'],
                    ['name' => '白癜风', 'value' => '1.31 倍', 'icon' => 'error'],
                    ['name' => '高血压', 'value' => '1.3 倍', 'icon' => 'error']
                );
                break;
            case 6:
            default:
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
                    ['name' => '前列腺癌', 'value' => '平均', 'icon' => 'normal'],
                    ['name' => '胰腺癌', 'value' => '平均', 'icon' => 'normal'],
                    ['name' => '肾癌', 'value' => '平均', 'icon' => 'normal'],
                    ['name' => '脑动脉瘤', 'value' => '平均', 'icon' => 'normal']
                );
                break;
        }
        $data['total'] = count($data['list']);
        $this->assign('data', $data);
        return $this->fetch();
    }
}