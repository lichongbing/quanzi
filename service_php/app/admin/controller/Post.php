<?php


namespace app\admin\controller;

use app\common\model\Post as P;
use think\facade\View;

class Post extends Base
{
    public function postList()
    {
        $list = P::withJoin(['userInfo'	=>	['username','avatar']])->order("id desc")->paginate(10,false,['query'=>request()->param()])->each(function($item, $key){
            if (!empty($item["media"])){
                $item["media"] = explode(",",$item["media"]);
                return $item;
            }
        });
        View::assign("postList",$list);
        return View::fetch("/post-list");
    }

    public function delPost()
    {
        $id = input("id");
        $res = P::destroy($id);
        if ($res){
            return json(["code" => 1 ,"msg" => "删除成功"]);
        }
        return json(["code" => 0 ,"msg" => "删除失败"]);
    }

}