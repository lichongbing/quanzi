<?php

namespace app\common\validate;

use think\Validate;

class User extends Validate
{
    protected $rule =   [
        'username'  => 'require|min:4|max:25|unique:user',
        'email' => 'email|unique:user',
        'password'   => 'require|min:6',
    ];

    protected $message  =   [
        'username.require' => '昵称必须',
        'username.max'     => '昵称最多不能超过25个字符',
        'username.min'     => '昵称最少4个字符',
        'username.unique' => '用户名已存在',
        'email.unique' => '邮箱已存在',
        'password.require'   => '密码必填',
        'password.min'  => '密码最少6个字符',
        'email'        => '邮箱格式错误',
    ];

}