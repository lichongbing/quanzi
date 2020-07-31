<?php


namespace app\common\validate;


use think\Validate;

class Post extends Validate
{
    protected $rule =   [
        'title'  => 'require|min:4|max:50',
        'content' =>'require'
    ];

    protected $message  =   [
        'title.require' => '标题不能为空',
        'title.min'     =>'标题最少四个字符',
        'title.max'     =>'标题不能超过50个字符',
        'content.require'   => '内容不能为空',
    ];
}