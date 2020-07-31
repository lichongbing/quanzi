<?php

namespace app\common\validate;

use think\Validate;

class Comment extends Validate
{
    protected $rule =   [
        'uid'  => 'number',
        'pid' => 'number',
        'content'   => 'require|max:255',
    ];

    protected $message  =   [
        'uid.number' => '非法uid',
        'pid.number' => '非法pid',
        'content.require' => '内容不能为空',
        'content.max' => '内容不能超过255字符',
    ];

}