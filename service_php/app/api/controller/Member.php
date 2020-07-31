<?php
namespace app\api\controller;
use app\common\model\User;
use think\facade\Db;

class Member extends Base
{

    public function wxLogin()
    {
        $APPID =  $this->APPID;
        $SECRET = $this->SECRET;

        $code = input("code");
        $username = input("username");
        $avatar = input("avatar");
        $province = input("province");
        $city = input("city");
        $gender = input("gender");

        $URL = "https://api.weixin.qq.com/sns/jscode2session?appid=".$APPID ."&secret=".$SECRET ."&js_code=".$code ."&grant_type=authorization_code";
        $result = curlData($URL);
        $openId = $result['openid'];
        $userInfo = Db::name("user")->where("openid",$openId)->find();
        $sessionKey = md5(rand(10000,99999).time());

        $data2 = [
            "username" => $username,
            "avatar" => $avatar,
            "openid" => $openId,
            "province" => $province,
            "city" => $city,
            "gender" => $gender,
            "group_id" => 2,
            "last_login_ip" => request()->ip()
        ];
        if($userInfo){
            cache($sessionKey,  $openId);
            User::update($data2,["openid" => $openId]);
            return json(["code" => 1 ,"sessionKey" =>$sessionKey]);
      
        }else{
            $userInfo =  User::create($data2);
            if($userInfo){
                cache($sessionKey,  $openId);
                return json(["code" => 1 ,"sessionKey" =>$sessionKey]);
            }
        }

        return json(["code" => 0 ,"msg" =>"错误"]);
    }

    //我的关注
    public  function follow()
    {
        $sessionKey = input("sessionKey");
        if (empty($sessionKey)){
            return json(["code" => 0 ,"sessionKey为空"]);
        }
        $uid = $this->getUid($sessionKey);
        $followUids = Db::name("follow")->where("uid",$uid)->column("follow_uid");
        $list = User::where("uid","in",$followUids)->withoutField("password,email,group_id,openid,status,user_type,create_time")->paginate(10,false,['query'=>request()->param()])->each(function($item, $key){
            $where["uid"] = $item["uid"];
            $where["follow_uid"] = $this->getUid(input("sessionKey"));

            $res1 = Db::name("follow")->where($where)->find();

            if ($res1){
                $item["has_follow"] = 1; //互相关注
            }else{
                $item["has_follow"] = 2; //已关注
            }

            return $item;
        });
        return json($list);
    }
    //我的粉丝
    public  function fans()
    {
        $sessionKey = input("sessionKey");
        if (empty($sessionKey)){
            return json(["code" => 0 ,"sessionKey为空"]);
        }
        $uid = $this->getUid($sessionKey);

        $fansUids = Db::name("follow")->where("follow_uid",$uid)->column("uid");
        $list = User::where("uid","in",$fansUids)->withoutField("password,email,group_id,openid,status,user_type,create_time")->paginate(10,false,['query'=>request()->param()])->each(function($item, $key){
            $where["uid"] = $this->getUid(input("sessionKey"));
            $where["follow_uid"] = $item["uid"];
            
            $res1 = Db::name("follow")->where($where)->find();

            if ($res1){
                $item["has_follow"] = 1; //互相关注
            }else{
                $item["has_follow"] = 0; //未关注
            }

            return $item;
        });
        return json($list);
    }

    //添加关注
    public function addFollow()
    {
        $sessionKey = input("sessionKey");
        if (empty($sessionKey)){
            return json(["code" => 0 ,"sessionKey为空"]);
        }
        $id = input("id");
        $uid = $this->getUid($sessionKey);
        if($id == $uid){
            return json(["code" => 0, "msg" => "不能关注自己"]);
        }
        $find = Db::name("follow")->where(["uid" => $uid,"follow_uid" => $id])->find();
        if ($find){
            return json(["code" => 0, "msg" =>"请勿重复关注"]);
        }
        $res = Db::name("follow")->insert(["uid" => $uid ,"follow_uid" => $id]);
        $username = Db::name("user")->where("uid",$uid)->value("username");
        if ($res){
            $this->sendMsg($uid,$id,0,4,"【".$username."】关注了你");
            return json(["code" => 1, "msg" => "关注成功"]);
        }
        return json(["code" => 0, "msg" => "关注失败"]);
    }

    public function isFollow()
    {
        $sessionKey = input("sessionKey");
        if (empty($sessionKey)){
            return json(["code" => 0 ,"sessionKey为空"]);
        }
        $id = input("id");
        $uid = $this->getUid($sessionKey);
        $find = Db::name("follow")->where(["uid" => $uid,"follow_uid" => $id])->find();
        if ($find){
            return json(["code" =>1,"msg" => "已关注"]);
        }
        return json(["code" =>0,"msg" => "未关注"]);
    }

    public function cancelFollow()
    {
        $sessionKey = input("sessionKey");

        $id = input("id");
        $uid = $this->getUid($sessionKey);
        $where["uid"] = $uid;
        $where["follow_uid"] = $id;
        $res = Db::name("follow")->where($where)->delete();
        if ($res){
            return json(["code" => 1 ,"msg" => "已取消关注"]);
        }
        return json(["code" => 0 ,"msg" => "错误"]);
    }

    public function userInfo()
    {
        $sessionKey = input("sessionKey");

        if (empty($sessionKey)){
            $uid = input("uid");
        }else{
            $uid = $this->getUid($sessionKey);
        }

        $userInfo = User::withoutField("group_id,openid,status,create_time")->find($uid);
        $userInfo["follow"] = Db::name("follow")->where("uid",$uid)->count();
        $userInfo["fans"] = Db::name("follow")->where("follow_uid",$uid)->count();
        $userInfo["post_num"] = Db::name("post")->where("uid",$uid)->count();

        return json($userInfo);
    }
}