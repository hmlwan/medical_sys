<?php
namespace app\index\validate;

use think\Validate;

class CertForm extends Validate
{
    protected $rule = [
        'real_name' => 'require',
        'card_id' => 'require',
        'mobile' => 'require',
    ];

    protected $message = [
        'real_name.require' => '姓名不能为空',
        'card_id.require' => '身份证不能为空',
        'mobile.require' => '手机号码不能为空',
    ];

}