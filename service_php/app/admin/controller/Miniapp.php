<?php

namespace app\admin\controller;

use think\facade\Db;
use think\facade\View;

class Miniapp
{

    public function baseSet()
    {
        if (request()->isAjax()){
            $appid = input("appid");
            $secret = input("secret");
            $data["value"] = $appid;
            $data["extend"] = $secret;
            $res = Db::name("system")->where("config","miniapp")->update($data);
            if ($res){
                return json(["code" => 1,"msg" =>"修改成功"]);
            }
            return json(["code" => 0,"msg" =>"修改失败"]);
        }else{
            $miniApp = Db::name("system")->where("config","miniapp")->find();
            View::assign("miniApp",$miniApp);
            return View::fetch("/miniapp-base-set");
        }
    }

    public function upload()
    {
        $file = request()->file("Image");
        try {
          \think\facade\Filesystem::disk('public')->putFile( 'uploads/default', $file,function (){return "wx_share_cover";});

            return json(["code" =>1,"msg" => "上传成功"]);
        } catch (think\exception\ValidateException $e) {
            return json(["code" =>0,"msg" =>$e->getMessage()]);
        }
    }
}