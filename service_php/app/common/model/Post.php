<?php


namespace app\common\model;


use think\Model;

class Post extends Model
{
    public function userInfo()
    {
        return $this->hasOne("User","uid","uid");
    }

    public function discuss()
    {
        return $this->hasOne("Discuss","id","discuss_id");
    }
}