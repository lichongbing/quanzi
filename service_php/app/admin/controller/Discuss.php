<?php


namespace app\admin\controller;

use app\common\model\Discuss as D;
use think\facade\View;

class Discuss extends Base
{

    public function list(){
        $list = D::withJoin(['userInfo'	=>	['username','avatar']])->order("id desc")->paginate(10);
        View::assign("list",$list);
        return View::fetch("/discuss-list");
    }

    public function del(){
        $id = input("id");
        $res = D::destroy($id);
        if ($res){
            return json(["code" => 1 ,"msg" => "删除成功"]);
        }
        return json(["code" => 0 ,"msg" => "删除失败"]);
    }
}