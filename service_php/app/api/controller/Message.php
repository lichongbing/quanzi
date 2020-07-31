<?php


namespace app\api\controller;
use app\common\model\Message as Msg;
use think\facade\Db;

class Message extends Base
{

    public function list()
    {
        $msgType = input("type");

        $sessionKey = input("sessionKey");
        $uid = $this->getUid($sessionKey);

        $where[] = ["to_uid","=",$uid];
        $where[] = ["message.status","in",[0,1]];

        if($msgType == 1){
            $where[] = ["type","in",[1,3]];
        }


        if($msgType == 3){
            $where[] = ["type","=",2];
        }

        if($msgType != 2){
            $list = Msg::withJoin(["userInfo" => ["username","avatar"],"postInfo"])->order("m_id desc")->where($where)->select();
        }else{
            $where[] = ["type","=",4];
            $list = Msg::withJoin(["userInfo" => ["username","avatar"]])->order("m_id desc")->where($where)->select();
        }
        return json($list);
    }

    public function status()
    {
        $msgType = input("type");

        $sessionKey = input("sessionKey");
        $uid = $this->getUid($sessionKey);

        $where[] = ["to_uid","=",$uid];

        if($msgType == 1){
            $where[] = ["type","in",[1,3]];
        }

        if($msgType == 2){
            $where[] = ["type","=",4];
        }

        if($msgType == 3){
            $where[] = ["type","=",2];
        }

        $res = Db::name("message")->where($where)->update(["status" => 1]);

        if ($res){
            return json(["code" => 1,"msg" => "消息状态修改成功"]);
        }
        return json(["code" => 0,"msg" => "消息状态修改失败"]);
    }

    public function num()
    {
        $sessionKey = input("sessionKey");
        $uid = $this->getUid($sessionKey);
        $where["status"] = 0;

        $thumbCollect = Db::name("message")->where("to_uid",$uid)->where($where)->where("type","in",[1,3])->count();
        $follow = Db::name("message")->where("to_uid",$uid)->where($where)->where("type",4)->count();
        $comment = Db::name("message")->where("to_uid",$uid)->where($where)->where("type",2)->count();

        $push_num = Db::name("message")->where("to_uid",$uid)->where($where)->where("type",6)->count();
        $push = Db::name("message")->where("to_uid",$uid)->where($where)->where("type",6)->order("m_id desc")->find();

        $sys_num = Db::name("message")->where("to_uid",$uid)->where($where)->where("type",5)->count();
        $sys = Db::name("message")->where("to_uid",$uid)->where($where)->where("type",5)->order("m_id desc")->find();

        return json([
            "code" =>1,
            "thumb_collect" => $thumbCollect,
            "follow" => $follow ,
            "comment" => $comment,
            "push" => ["num" => $push_num,"newest" => $push],
            "sys" => ["num" => $sys_num,"newest" => $sys]
        ]);
    }

    public function delMsg()
    {
        $id = input("id","","intval");
        $res = Msg::update(["status" => -1],["m_id" => $id]);
        if ($res){
            return json(["code" => 1,"msg" => "删除成功"]);
        }
        return json(["code" => 0,"msg" => "删除失败"]);
    }
}