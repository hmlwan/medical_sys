<?php
namespace app\admin\validate;

use think\Request;
use think\Validate;

class ShopConfigForm extends Validate
{

    protected $rule = [
        'title' => 'require',
        'price' => 'require',
    ];

    protected $message = [
        'title.require' => '名称不能为空',
        'price.require' => '请输入价格',
    ];


}