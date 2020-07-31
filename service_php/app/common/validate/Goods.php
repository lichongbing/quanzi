<?php


namespace app\common\validate;

use think\Validate;

class Goods extends Validate
{
    protected $rule =   [
        'title'  => 'require|min:4|max:50',
        'price'  => 'require',
        'introduce' =>'require',
        'download_url' =>'url'
    ];

    protected $message  =   [
        'title.require' => '标题不能为空',
        'title.min'     =>'标题最少4个字符',
        'title.max'     =>'标题不能超过50个字符',
        'price.require'     =>'价格不能为空',
        'introduce.require'   => '商品介绍不能为空',
        'download_url.url'   => '资源地址不正确',
    ];
}