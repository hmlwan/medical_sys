<?php
namespace app\index\validate;

use think\Validate;

class TradePasswordForm extends Validate
{
    protected $rule = [
        'new_pwd' => 'require',
        'confirm_pwd' => 'require|confirm:new_pwd|min:8',
        'code' => 'require'
    ];

    protected $message = [
        'new_pwd.require' => '新密码不能为空',
        'confirm_pwd.require' => '确定密码不能为空',
        'confirm_pwd.confirm' => '两次密码不一样',
        'code.require' => '验证码不能为空',
        'confirm_pwd.min' => '密码至少为8位'
    ];

}