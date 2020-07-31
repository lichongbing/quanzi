<?php

namespace app\admin\controller;

use think\facade\Db;
use think\facade\View;
use app\common\model\User as U;
use app\common\model\Group;
use app\common\validate\User as UserVali;
use think\exception\ValidateException;
class User extends Base
{
    public function login()
    {
        if (request()->isAjax()){
            $data = input("post.");
            $data["password"] = md5($data["password"]);
            $uid = Db::name("user")->where($data)->value("uid");
            if ($uid){
                session("AdminId",$uid);
                return json(["code" => 1,"msg" => "登录成功"]);
            }
            return json(["code" => 0,"msg" => "用户名或密码错误"]);
        }else{
            return View::fetch();
        }
    }

    public function logout()
    {
        session("AdminId",null);
        return redirect("/admin/user/login");
    }

    public function userList()
    {

        $list = U::with(["group"])->order("update_time desc")->paginate(10);
        View::assign("list",$list);
        return View::fetch('/user-list');

    }

    public function adminRole()
    {
        $list = Group::select();
        View::assign("list",$list);
        return View::fetch('/admin-role');
    }

    public function adminRule()
    {
        return View::fetch('/admin-rule');
    }

    public function delGroup()
    {
        $id = input("id");
        if ($id == 1 || $id == 2 ){
            return json(["code" => 0 ,"msg" => "系统默认用户组不可删除"]);
        }
        $del = Db::name("group")->where("group_id",$id)->delete();
        if ($del){
            return json(["code" => 1 ,"msg" => "删除成功"]);
        }
        return json(["code" => 0 ,"msg" => "删除失败"]);
    }

    public function addRole()
    {
        $roleName = input("roleName");
        $id = input("id");
        if (empty($roleName)){
            return json(["code" => 0 ,"msg" => "角色名不能为空"]);
        }
        if (empty($id)){
            $addRole = Group::create(["name" => $roleName]);
            if ($addRole){
                return json(["code" => 1 ,"msg" => "添加成功"]);
            }
            return json(["code" => 0 ,"msg" => "添加失败"]);
        }else{
            $updateRole = Group::update(["name" => $roleName],["group_id" => $id]);
            if ($updateRole){
                return json(["code" => 1 ,"msg" => "修改成功"]);
            }
            return json(["code" => 0 ,"msg" => "修改失败"]);
        }
    }

    public function groupRule()
    {

        if (request()->isAjax()){
            //用户组拥有的权限
            $id = input("id");
            $ruleIdList = Db::name("group_rule")->where("group_id",$id)->column("rule_id");
            return json($ruleIdList);
        }else{
            //列出所有权限
            $ruleIdList = Db::name("rule")->column("id");
            $ruleList = array();
            foreach ($ruleIdList as $item){
                $ruleItem = Db::name("rule")->withoutField("name")->where("id",$item)->find();
                $ruleList[$ruleItem["pid"]][] = $ruleItem;
            }
            View::assign("ruleList",$ruleList);
            return View::fetch("/group-rule");
        }
    }

    public function saveRule()
    {
        $groupId = input("groupId");
        $ruleIdList = input("post.ruleIdList/a");

        if ($groupId ==1){
            return json(["code" =>0 ,"msg" => "不能修改超级管理员权限"]);
        }

        $isGroupRule = Db::name('group_rule')->where('group_id', $groupId)->find();
        if ($isGroupRule){
            $delGroup = Db::name('group_rule')->where('group_id', $groupId)->delete();
            if(!$delGroup){
                return json(["code" =>0 ,"msg" => "权限修改失败了"]);
            }
        }
        foreach($ruleIdList as $ruleId){

            $data["group_id"] = $groupId;
            $data["rule_id"] = $ruleId;

            $pid = Db::name("rule")->where("id",$ruleId)->value("pid");
            $isRule = Db::name("group_rule")->where(["group_id" =>$groupId,"rule_id" =>$pid])->find();

            if ($pid > 0 && !$isRule){
                Db::name("group_rule")->insert(["group_id" => $groupId,"rule_id" =>$pid]);
            }
            $createRule =  Db::name("group_rule")->insert($data);
            if(!$createRule){
                return json(["code" =>0 ,"msg" => "权限修改失败"]);
            }

        }
        return json(["code" =>1 ,"msg" => "权限修改成功"]);
    }
    public function userAdd()
    {
        if (request()->isAjax()){
            $data = input("post.");
             try {
                 validate(UserVali::class)->check($data);
             } catch (ValidateException $e) {
                 // 验证失败 输出错误信息
                return json(["code" => 0,"msg" =>$e->getError()]);
             }

            $data["password"] = md5($data["password"]);
             if (!empty($data["uid"])){
                 $rel = U::update($data,["uid" => $data["uid"]]);
                 if ($rel){
                     return json(["code" => 1,"msg" =>"修改成功"]);
                 }
             }else{
                 $isEmail = U::where("email",$data["email"])->find();
                 $isUserName = U::where("username",$data["username"])->find();
                 if ($isUserName){
                     return json(["code" => 0,"msg" =>$data["username"] ."，用户名已存在"]);
                 }
                 if ($isEmail){
                     return json(["code" => 0,"msg" =>$data["email"] ."，邮箱已存在"]);
                 }

                 $createUser = U::create($data);
                 if ($createUser){
                     return json(["code" => 1,"msg" =>"添加成功"]);
                 }
                 return json(["code" => 0,"msg" =>"添加失败"]);
             }
        }else{
            $uid = input("uid");
            $userInfo = array();
            if (!empty($uid)){
                $userInfo = Db::name("user")->where("uid",$uid)->find();
            }
            View::assign("userInfo",$userInfo);
            $groupList = Db::name("group")->select();
            View::assign("groupList",$groupList);
            return View::fetch("/user-add");
        }
    }

    public function delUser()
    {
        $UID = input("UID");

        if ($UID == 1){
            return json(["code" => 0,"msg" => "默认超级管理员账号，不可删除"]);
        }

        if ($UID == session("AdminId")){
            return json(["code" => 0,"msg" => "当前登录用户，不可删除"]);
        }

       $del = U::destroy($UID);
       if ($del){
           return json(["code" => 1,"msg" => "删除成功"]);
       }
        return json(["code" => 0,"msg" => "删除失败"]);
    }

    public function userStatus()
    {
        $status = input("status");
        $uid = input("uid");
        $rel = U::update(["status" => $status],["uid" => $uid]);
        if ($rel){
            return json(["code" => 1,"msg" => "状态修改成功"]);
        }
        return json(["code" => 0,"msg" => "状态修改失败"]);
    }
}