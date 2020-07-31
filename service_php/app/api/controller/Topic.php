<?php


namespace app\api\controller;


use app\common\model\User;
use think\facade\Db;
use app\common\model\Topic as T;
class Topic extends Base
{

    protected function listSQL($where = [],$order="",$field="",$paginate = 10){

        $list = T::where($where)->order($order)->field($field)->paginate($paginate,false,['query'=>request()->param()])->each(function($item, $key){
            $topicId = $item["id"];
            $topicPostCount = Db::name("post")->where("topic_id",$topicId)->count();
            $topicUserCount = Db::name("user_topic")->where("topic_id",$topicId)->count();

            if (empty($item["cover_image"])){
                $imgNum = rand(1,14);
                $item["cover_image"] = request()->domain() ."/static/images/api/topic_default/topic_default_cover_".$imgNum .".jpg";
            }else{
                if (strpos($item["cover_image"],"http://") === false && strpos($item["cover_image"],"https://") === false){
                    $item["cover_image"] = request()->domain() .$item["cover_image"];
                }
            }
            $item['postCount'] = $topicPostCount;
            $item['userCount'] = $topicUserCount;
            return $item;
        });
        return $list;

    }


    public function userTopicList()
    {
        $uid = input("uid");
        $topicIds = Db::name("user_topic")->where("uid",$uid)->column("topic_id");
        $where[] = ["id","in",$topicIds];
        $topicList = $this->listSQL($where,"id desc","id,topic_name,cover_image,description,bg_image",999);
        return json($topicList);
    }

    public function topicDetail()
    {
        $id = input("id");
        $detail = Db::name("topic")->where("id",$id)->find();


        if(empty($detail["bg_image"])){
            $detail["bg_image"] = request()->domain() ."/static/images/api/topic_default/topic_default_cover_3.jpg";
        }
        if(empty($detail["cover_image"])){
            $detail["cover_image"] = request()->domain() ."/static/images/api/topic_default/topic_default_cover_3.jpg";
        }else{
            $pattern="#(http|https)://(.*\.)?.*\..*#i";
            if(!preg_match($pattern,$detail["cover_image"])){
                $detail["cover_image"] = request()->domain() .$detail["cover_image"];
            }
        }

        $uids = Db::name("user_topic")->where("topic_id",$id)->column('uid');

        $detail["postCount"] = Db::name("post")->where("topic_id", $id)->count();
        $detail["userCount"] = Db::name("user_topic")->where("topic_id", $id)->count();
        $detail["discuss_list"] = Db::name("discuss")->where("topic_id",$id)->limit(5)->select();
        $detail["member_list"] = User::order("update_time desc")->limit(5)->select($uids);

        return json($detail);
    }

    //我创建的圈子
    public function myCreateTopic()
    {
        $sessionKey = input("sessionKey");
        $uid = $this->getUid($sessionKey);

        $where["uid"] = $uid;
        $topicList = $this->listSQL($where,"id desc","id,topic_name,cover_image");
        return json($topicList);
    }

    public function myTopic()
    {
        $sessionKey = input("sessionKey");
        $uid = $this->getUid($sessionKey);
        $topicIds = Db::name("user_topic")->where("uid",$uid)->column("topic_id");
        $where[] = ["id","in",$topicIds];
        $topicList = $this->listSQL($where,"id desc","id,topic_name,cover_image",999);
        return json($topicList);
    }

    //推荐圈子
    public function recTopic()
    {
        $ids = [1,6,7,8,9,12,13,17];
        $where[] = ["id","in",$ids];
        $list = $this->listSQL($where);
        return json($list);
    }

    public function cateTopic(){
        $cateId = input("id");
        $where = [];

        if($cateId > 0){
            $where[] = ["cate_id","=",$cateId];
        }

        $list = $this->listSQL($where,"id desc","id,topic_name,cover_image");
        return json($list);
    }

    public function cateList()
    {
        $list = Db::name("topic_cate")->select()->toArray();
        array_unshift($list,["id" => 0 ,"name" => "推荐"]);
        return json($list);
    }
    //退出圈子
    public function userTopicDel()
    {
        $sessionKey = input("sessionKey");
        $topicId = input("topicId");
        $uid = $this->getUid($sessionKey);

        $where[] = ["uid","=",$uid];
        $where[] = ["topic_id","=",$topicId];

        $res = Db::name("user_topic")->where($where)->delete();

        if ($res){
            return json(["code" => 1 ,"msg" => "已退出该圈子"]);
        }
        return json(["code" => 0 ,"msg" => "操作失败"]);
    }

    public function isJoinTopic()
    {
        $sessionKey = input("sessionKey");
        $topicId = input("topicId");
        $uid = $this->getUid($sessionKey);

        $where[] = ["uid","=",$uid];
        $where[] = ["topic_id","=",$topicId];

        $res = Db::name("user_topic")->where($where)->find();

        if ($res){
            return json(["code" => 1 ,"msg" => "已加入该圈子"]);
        }
        return json(["code" => 0 ,"msg" => "没有加入该圈子"]);
    }

    public function joinTopic()
    {
        $sessionKey = input("sessionKey");
        $topicId = input("topicId");
        $uid = $this->getUid($sessionKey);

        $data["uid"] = $uid;
        $data["topic_id"] = $topicId;

        $res = Db::name("user_topic")->insert($data);

        if ($res){
            return json(["code" => 1 ,"msg" => "已加入该圈子"]);
        }
        return json(["code" => 0 ,"msg" => "发生错误"]);

    }

    //添加圈子
    public function topicAdd(){
        $uid = $this->getUid(input("sessionKey"));

        $data["topic_name"] = input("title");
        $data["cover_image"] = input("imgUrl");
        $data["uid"] = $uid;
        $data["cate_id"] = input("cateId");

        $res = T::create($data);
        if ($res){
            return json(["code" => 1 ,"id" => $res->id,"msg" => "话题创建成功"]);
        }
        return json(["code" => 0 ,"msg" => "话题创建失败"]);
    }
}