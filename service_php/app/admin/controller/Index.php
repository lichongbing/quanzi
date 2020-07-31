<?php
namespace app\admin\controller;

use think\App;
use think\facade\Db;
use think\facade\View;

class Index extends Base
{
    public function __construct(App $app)
    {
        $username = Db::name("user")->where("uid",session("AdminId"))->value("username");
        View::assign("username",$username);
        parent::__construct($app);
    }

    public function index()
    {

        if (!session("AdminId")){
            return redirect('/admin/user/login');
        }

        //功能菜单
        $groupId = Db::name("user")->where("uid",session("AdminId"))->value("group_id");
        $ruleIdList = Db::name("group_rule")->where("group_id",$groupId)->column("rule_id");
        $menuList = array();
        foreach ($ruleIdList as $key => $item){
            $where["id"] = $item;
            $where["menu"] = 0;
            $ruleItem = Db::name("rule")->where($where)->find();
            if (!$ruleItem){
                continue;
            }
            $menuList[$ruleItem["pid"]][] = $ruleItem;
        }
        View::assign("menu",$menuList);
        return View::fetch("/index");
    }

    public function welcome()
    {

        $postCount = Db::name("post")->count();
        $userCount = Db::name("user")->count();
        View::assign("SYSTEM_VERSION",SYSTEM_VERSION);
        View::assign("postCount",$postCount);
        View::assign("userCount",$userCount);
        return View::fetch("/welcome");
    }

    public function upload(){

        $file = request()->file("Image");
        $check = new \app\api\controller\Base();

        $uploadType = Db::name("system")->where("config","uploadType")->value("value");
        if ($uploadType == 1){
            //上传本地服务器
            try {
                $path = request()->domain() ."/" . \think\facade\Filesystem::disk('public')->putFile( 'uploads/postImages', $file);
                $savename = str_replace("\\","/",$path);

                if ($check->imgCheck($savename) != 0){
                    return json(["code" =>0,"msg" =>"内容违法违规"]);
                }

                return json(["code" =>1,"msg" =>"上传成功","url" =>$savename]);
            } catch (\think\exception\ValidateException $e) {
                return json(["code" =>0,"msg" =>$e->getMessage()]);
            }
        }elseif ($uploadType == 2){
            //上传阿里云OSS
            $savename = [];
            $res = alYunOSS($file, $file->extension());
            if ($res["code"] === 1){
                array_push($savename,$res["url"]);
            }elseif ($res["code"] === 0){
                return json(["code" =>0,"msg" =>$res["msg"]]);
            }

            if ($check->imgCheck($savename[0]) != 0){
                return json(["code" =>0,"msg" =>"内容违法违规"]);
            }

            return json(["code" =>1,"msg" =>"上传成功","url" =>$savename]);
        }
    }
}
