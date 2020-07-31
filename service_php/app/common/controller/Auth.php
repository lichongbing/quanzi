<?php

namespace app\common\controller;


use think\facade\Db;

class Auth
{
    public function check($name,$uid,$filter = null)
    {
        foreach ( $filter as $item){
            if ($item == $name){
                return true;
            }
        }

        $userGroupId = Db::name("user")->where("uid",$uid)->value("group_id");
        $groupRuleListId = Db::name("group_rule")->where("group_id",$userGroupId)->column("rule_id");

        foreach ($groupRuleListId as $id){
            $ruleItem = Db::name("rule")->where("id",$id)->value("name");
            if ($ruleItem == $name){
                return true;
            }
        }
    }

}