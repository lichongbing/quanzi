<?php


namespace app\admin\controller;


use think\facade\Db;
use think\facade\View;
use app\common\model\TopicCate;
use app\common\model\Topic as T;
class Topic extends Base
{

    public function cate(){
        $list = Db::name("topic_cate")->select();
        View::assign("cateList",$list);
        return View::fetch("/topic-cate");
    }

    public function cateEdit(){
        $id = input("cate_id");
        $cateName = input("cate_name");
        if (request()->isAjax()){
            $data["id"] = $id;
            $data["name"] = $cateName;
            $res = Db::name("topic_cate")->update($data);
            if ($res){
                return json(["code" => 1 ,"msg" => "保存成功"]);
            }
            return json(["code" => 0 ,"msg" => "保存失败"]);
        }else{
            $cateInfo = Db::name("topic_cate")->where("id",$id)->find();
            View::assign("cateInfo",$cateInfo);
            return View::fetch("/topic-cate-edit");
        }
    }

    public  function cateDel(){
        $id = input("cate_id");
        $res = TopicCate::destroy($id);
        if ($res){
            return json(["code" => 1 ,"msg" => "删除成功"]);
        }
        return json(["code" => 0 ,"msg" => "删除失败"]);
    }

    public function cateAdd(){
        $cateName = input("cateName");
        $res = Db::name("topic_cate")->insert(["name" => $cateName]);
        if ($res){
            return json(["code" => 1 ,"msg" => "添加成功"]);
        }
        return json(["code" => 0 ,"msg" => "添加失败"]);
    }
    public function list(){
        $list = T::withJoin(['userInfo'	=>	['username','avatar']])->order("id desc")->paginate(10);
        View::assign("list",$list);
        return View::fetch("/topic-list");
    }

    public function topicDel(){
        $id = input("id");
        $res = T::destroy($id);
        if ($res){
            return json(["code" => 1 ,"msg" => "删除成功"]);
        }
        return json(["code" => 0 ,"msg" => "删除失败"]);
    }
}