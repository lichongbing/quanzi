<?php


namespace app\api\controller;
use app\common\model\Discuss as D;
use think\facade\Db;

class Discuss extends Base
{

    protected function listSQL($where="",$order="id desc" ,$paginate = 10)
    {
        $list = D::where($where)->order($order)->paginate($paginate);
        return $list;
    }

    public function addDis()
    {
        $data["title"] = input("title");
        $data["introduce"] = input("intro");

        $uid = $this->getUid(input("sessionKey"));
        $data["uid"] = $uid;
        $res = D::create($data);
        if ($res){
            return json(["code" => 1 ,"id" => $res->id,"msg" => "话题创建成功"]);
        }
        return json(["code" => 0 ,"msg" => "话题创建失败"]);
    }

    public function detail()
    {
        $id = input("id");
        Db::name('discuss')->where('id', $id)->inc('read_count')->update();
        $res = D::withJoin(["userInfo" => ["username","avatar"]])->where("id",$id)->find();
        $res["post_count"] = D::name("post")->where("discuss_id",$id)->count();
        return json($res);
    }

    public function list()
    {
        $active = input("active");
        $paginate = 10;
        if ($paginate){
            $paginate = input("paginate");
        }
        if ($active == "hot"){
            //热门话题
            $list = self::listSQL("","read_count desc",$paginate);
        }elseif ($active == "new"){
            //最新话题
            $list = self::listSQL("","id desc",$paginate);
        }else{

            //我关注的话题
            $sessionKey = input("sessionKey");
            $uid = $this->getUid($sessionKey);

            $discussIds = Db::name("user_discuss")->where("uid",$uid)->column("discuss_id");

            $where[] = ["id","in",$discussIds];
            $list = self::listSQL($where);
        }

        return json($list);
    }

    //我创建的话题
    public function myDis()
    {
        $sessionKey = input("sessionKey");
        $uid = $this->getUid($sessionKey);

        $where["uid"] = $uid;
        $list = self::listSQL($where);

        return json($list);
    }
}