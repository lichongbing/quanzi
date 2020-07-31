<?php


namespace app\common\model;


use think\Model;

class Topic extends Model
{

    public function userInfo()
    {
        return $this->hasOne("User","uid","uid");
    }

}