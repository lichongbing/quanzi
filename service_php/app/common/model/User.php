<?php

namespace app\common\model;


use think\Model;

class User extends Model
{
    protected $pk = 'uid';
    protected $autoWriteTimestamp = true;
    public function group()
    {
        return $this->hasOne("Group","group_id","group_id");
    }

    public function getGenderAttr($value)
    {
        $status = [0=>'未知',1=>'男',2=>'女'];
        return $status[$value];
    }
}