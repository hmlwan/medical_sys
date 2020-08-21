<?php
namespace app\admin\validate;

use think\Request;
use think\Validate;

class StarConfigForm extends Validate
{

    protected $rule = [
        'star_name' => 'require',
        'detail_bg_color' => 'require',
        'receive_bg_color' => 'require',
        'cert_num' => 'require',
        'energy_num' => 'require',
        'reward_num' => 'require',


    ];

    protected $message = [
        'star_name.require' => '星级名称不能为空',
        'detail_bg_color.require' => '请输入详情背景色',
        'receive_bg_color.require' => '请输入领取背景色',
        'cert_num.require' => '请输入2级的实名人数',
        'energy_num.require' => '请输入2级总算力数',
        'reward_num.require' => '请输入奖励云链数量',

    ];


}