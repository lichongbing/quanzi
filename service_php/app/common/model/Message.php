<?php


namespace app\common\model;


use think\Model;

class Message extends Model
{
    protected $pk = "m_id";
    public function userInfo()
    {
        return $this->hasOne("User","uid","from_uid");
    }

    public function postInfo()
    {
        return $this->hasOne("Post","id","post_id");
    }
}