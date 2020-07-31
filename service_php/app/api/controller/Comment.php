<?php


namespace app\api\controller;


use think\facade\Db;

class Comment extends Base
{
    public function addComment()
    {
        $sessionKey = input("sessionKey");
        if (empty($sessionKey)){
            return json(["code" => 0 ,"sessionKey为空"]);
        }
        $content = input("content");

        if ($this->checkText($content) != 0){
            return json(["code" => 0 ,"msg" =>"内容违法违规"]);
        }
        $postId = input("aid");
        $to_uid = input("uid");
        $uid = $this->getUid($sessionKey);
        $res = \app\common\model\Comment::create(["uid" => $uid, "post_id" => $postId,"content" => $content]);
        if ($res){
            $this->sendMsg($uid, $to_uid, $postId,2,"评论：".$content);
            return json(["code" => 1 ,"msg" => "评论成功"]);
        }
        return json(["code" => 0 ,"msg" => "错误"]);
    }
}