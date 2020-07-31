<?php


namespace app\api\controller;

use app\common\model\Post as P;
use app\common\model\Comment;
use think\facade\Db;

class Post extends Base
{

    protected function listSQL($where=[],$order="id desc")
    {
        $list = P::withJoin(['userInfo'	=>	['username','avatar']])->where($where)->order($order)->paginate(10,false,['query'=>request()->param()])->each(function($item, $key){

            $item["comment_count"] = Db::name("comment")->where("post_id",$item["id"])->count();
            $item["fabulous_count"] = Db::name("post_fabulous")->where("post_id",$item["id"])->count();

            if ($item["discuss_id"] > 0){
                $item["discuss_title"] = Db::name("discuss")->where("id",$item["discuss_id"])->value("title");
            }
            if (!empty($item["media"])){
                $item["media"] = explode(",",$item["media"]);
                return $item;
            }else{
                $item["media"] = [];
            }
        });

        return $list;
    }

    /**
     * 话题帖子
     */
    public function discussPost()
    {
        $id = input("id");
        $where[] = ["discuss_id","=",$id];
        $list = $this->listSQL($where);
        return json($list);
    }

    public function topicPost()
    {
        $orderType = input("order");
        $order = "read_count desc";

        if ($orderType == "news"){
            $order = "create_time desc";
        }

        $topicId = input("topicId");
        $where[] = ["topic_id","=",$topicId];
        $list = $this->listSQL($where,$order);
        return json($list);
    }

    public function myPost()
    {
        $sessionKey = input("sessionKey");

        $uid = $this->getUid($sessionKey);
        $where["post.uid"] = $uid;

        $list = $this->listSQL($where);
        return json($list);
    }

    public function myCollectPost()
    {
        $sessionKey = input("sessionKey");

        $uid = $this->getUid($sessionKey);

        $postIds = Db::name("post_collection")->where("uid",$uid)->column("post_id");

        $where[] = ["id","in",$postIds];
        $list = $this->listSQL($where);

        return json($list);
    }
    /**
     * 热门贴子
     */
    public function hotPost()
    {
        $list = $this->listSQL([],"read_count desc");
        return json($list);
    }
    /**
     * 最新贴子
     */
    public function allPost()
    {
        $where[] = ["topic_id","in",[0,1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18]];
        $list = $this->listSQL($where);
        return json($list);
    }

    /**
     * 关注用户的贴子
     */
    public function followUserPost()
    {
        $sessionKey = input("sessionKey");
        $uid = $this->getUid($sessionKey);

        $followUids = Db::name("follow")->where("uid",$uid)->column("follow_uid");

        $where[] = array("post.uid","in",$followUids);

        $list = $this->listSQL($where);
        return json($list);
    }

    /**
     * 用户的贴子
     */
    public function userPost()
    {
        $uid = input("uid","","intval");

        $where["post.uid"] = $uid;
        $list = $this->listSQL($where);
        return json($list);
    }

    public function addPost()
    {
        $content = input("content");
        $sessionKey = input("sessionKey");
        $topicId = input("topicId");
        $discussId = input("discussId");
        $media = input("media");
        if (empty($sessionKey)){
            return json(["code" => 0 ,"sessionKey为空"]);
        }

        if ($this->checkText($content) != 0){
            return json(["code" => 0 ,"msg" =>"内容违法违规"]);
        }

        if($discussId > 0){
            $topicId = Db::name("discuss")->where("id",$discussId)->value("topic_id");
        }

        $this->checkText($content);
        $uid = $this->getUid($sessionKey);

        $data = [
            "uid" => $uid,
            "content" => $content,
            "topic_id" => $topicId,
            "discuss_id" => $discussId,
            "media" => $media
        ];
        $addPost = P::create($data);
        if ($addPost){
            return json(["code" => 1,"pid" =>$addPost->id,"msg" => "发布成功"]);
        }
        return json(["code" => 0,"msg" =>"发布失败"]);
    }

    public function delPost()
    {
        $sessionKey = input("sessionKey");
        if (empty($sessionKey)){
            return json(["code" => 0 ,"sessionKey为空"]);
        }
        $id = input("id");
        $uid = $this->getUid($sessionKey);
        $where["uid"] = $uid;
        $where["id"] = $id;
        $isEmpty = P::where($where)->find();
        if (!$isEmpty){
            return json(["code" => 0,"msg" =>"只可删除自己发布的动态"]);
        }

        $res = P::destroy($id);
        if ($res){
            return json(["code" => 1,"msg" =>"删除成功"]);
        }
        return json(["code" => 0,"msg" =>"删除失败"]);
    }

    public function updateImage()
    {
        $pid = input("pid");
        $media = input("media");
        $res = P::update(["media" =>$media],["id" => $pid]);
        if ($res){
            return json(["code" => 1,"msg" =>"发布成功"]);
        }
        return json(["code" => 0,"code" =>"发布失败"]);
    }

    public  function detail()
    {
        $id = input("id");
        Db::name('post')->where('id', $id)->inc('read_count')->update();
        $res = P::withJoin(['userInfo'	=>	['username','avatar','intro']])->find($id);
        $comment = Comment::withJoin(['userInfo' => ['username','avatar']])->order("id desc")->where("post_id",$id)->select();
        $res["comment"] = $comment;

        if (!empty($res["media"])){
            $res["media"] = explode(",",$res["media"]);
        }else{
            $res["media"] = [];
        }

        return json($res);
    }

    /**
     * 是否收藏
     *
     **/
    public function isCollection()
    {
        $sessionKey = input("sessionKey");
        $uid = $this->getUid($sessionKey);
        $pid = input("id");
        $where["uid"] = $uid;
        $where["post_id"] = $pid;
        $res = Db::name("post_collection")->where($where)->find();
        if ($res){
            return json(["code" => 0, "msg" => "已收藏"]);
        }
        return json(["code" => 1, "msg" => "未收藏"]);
    }

    public function addCollection()
    {
        $sessionKey = input("sessionKey");
        $uid = $this->getUid($sessionKey);
        $postId = input("id");
        $to_uid = input("uid");
        $data["uid"] = $uid;
        $data["post_id"] = $postId;
        $isCollection =  $res = Db::name("post_collection")->where($data)->find();
        if ($isCollection){
            return json(["code" => 0, "msg" => "请勿重复收藏"]);
        }

        $res = Db::name("post_collection")->insert($data);

        if ($res){
            $this->sendMsg($uid, $to_uid, $postId,3,"收藏了您的文章");
            return json(["code" => 1 ,"msg" => "收藏成功"]);
        }
        return json(["code" => 0 ,"msg" => "收藏失败"]);
    }

    public function cancelCollection()
    {
        $sessionKey = input("sessionKey");
        $uid = $this->getUid($sessionKey);
        $postId = input("id");
        $where["uid"] = $uid;
        $where["post_id"] = $postId;
        $res = Db::name("post_collection")->where($where)->delete();

        if ($res){
            return json(["code" => 1 ,"msg" => "已取消收藏"]);
        }
        return json(["code" => 0 ,"msg" => "取消收藏失败"]);
    }

    /**
     * 是否点赞帖子
     *
     **/
    public function isThumb()
    {
        $sessionKey = input("sessionKey");
        if (empty($sessionKey)){
            return json(["code" => "error" ,"sessionKey为空"]);
        }
        $uid = $this->getUid($sessionKey);
        $postId = input("id");
        $where["uid"] = $uid;
        $where["post_id"] = $postId;
        $res = Db::name("post_fabulous")->where($where)->find();
        if ($res){
            return json(["code" => 0, "msg" => "已点赞"]);
        }
        return json(["code" => 1, "msg" => "未点赞"]);
    }
    public function addThumb()
    {
        $sessionKey = input("sessionKey");
        $uid = $this->getUid($sessionKey);
        $postId = input("id");
        $to_uid = input("uid");
        $data["uid"] = $uid;
        $data["post_id"] = $postId;
        $isCollection =  $res = Db::name("post_fabulous")->where($data)->find();
        if ($isCollection){
            return json(["code" => 0, "msg" => "请勿重复点赞"]);
        }
        $res = Db::name("post_fabulous")->insert($data);

        if ($res){
            $this->sendMsg($uid, $to_uid, $postId,1,"点赞了您的文章");
            return json(["code" => 1 ,"msg" => "点赞成功"]);
        }
        return json(["code" => 0 ,"msg" => "点赞失败"]);
    }

    public function cancelThumb()
    {
        $sessionKey = input("sessionKey");
        $uid = $this->getUid($sessionKey);
        $postId = input("id");
        $where["uid"] = $uid;
        $where["post_id"] = $postId;

        $res = Db::name("post_fabulous")->where($where)->delete();

        if ($res){
            return json(["code" => 1 ,"msg" => "已取消点赞"]);
        }
        return json(["code" => 0 ,"msg" => "取消点赞失败"]);
    }
}