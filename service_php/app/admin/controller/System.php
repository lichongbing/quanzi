<?php

namespace app\admin\controller;

use think\facade\Db;
use app\common\model\Link;
class System extends Base
{

    public function Basics()
    {

        //上传配置
        $uploadType = Db::name("system")->where("config","uploadType")->find();
        $uploadType["extend"] = json_decode($uploadType["extend"],true);
        return view("/basics",[
            "uploadType" => $uploadType
        ]);
    }

    //上传配置
    public function uploadType()
    {
        $data = input("post.");
        if ($data["uploadType"] == 1){
            $insertData = ["value" =>$data["uploadType"]];
        }elseif ($data["uploadType"] == 2){
            $insertData = ["value" =>$data["uploadType"],"extend" =>json_encode(["endpoint" => $data["OssAddress"],"OssName" => $data["OssName"],"accessKeyId" => $data["keyId"],"accessKeySecret"=>$data["keySecret"]])];
        }
        $update = Db::name("system")->where("config","uploadType")->update($insertData);
        if($update){
            return json(["code" => 1, "msg" => "保存成功"]);
        }
        return json(["code" => 0, "msg" => "保存失败"]);
    }
    public function deleteLink()
    {
        $linkId = input("linkId");
        $deleteLink = Db::name("link")->where("link_id",$linkId)->delete();
        if($deleteLink){
            return json(["code" => 1, "msg" =>"删除成功"]);
        }else{
            return json(["code" => 0, "msg" =>"删除失败"]);
        }
    }
}