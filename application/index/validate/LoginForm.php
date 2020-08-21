<?php
namespace app\index\validate;

use think\Validate;

class LoginForm extends Validate
{
    protected $rule = [
        'account' => 'require',
        'password' => 'require'
    ];

    protected $message = [
        'account.require' => '手机号不能为空',
        'password.require' => '密码不能为空'
    ];

}