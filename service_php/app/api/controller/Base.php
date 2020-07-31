<?php

namespace app\api\controller;
use think\facade\Db;
class Base
{
    protected $APPID;

    protected $SECRET;

    public function __construct()
    {
        $miniApp = Db::name("system")->where("config","miniapp")->find();
        $this->APPID = $miniApp["value"];
        $this->SECRET = $miniApp["extend"];
    }

    public function getUid($sessionKey)
    {
        $UID = Db::name("user")->where("openid",cache($sessionKey))->value("uid");
        return $UID;
    }

    public  function getAccessToken()
    {
        $URL = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->APPID ."&secret=" .$this->SECRET;
        $res = curlData($URL);
        return $res["access_token"];
    }

    public function checkText($content)
    {
        $Url = "https://api.weixin.qq.com/wxa/msg_sec_check?access_token=" .$this->getAccessToken();
        $res = curlData($Url,json_encode(["content" => $content],JSON_UNESCAPED_UNICODE),"POST");
        return $res["errcode"];
    }


    public function sendMsg($from_uid,$to_uid,$post_id,$type,$content)
    {

        /**
         * type 1为点赞，2为评论  3为收藏 4为关注  5为系统消息  6为推送消息
         */
        $data["from_uid"] = $from_uid;
        $data["to_uid"] = $to_uid;
        $data["post_id"] = $post_id;
        $data["content"] = $content;
        $data["type"] = $type;
        $data["create_time"] = time();

        $where["from_uid"] = $from_uid;
        $where["to_uid"] = $to_uid;
        $where["type"] = $type;
        $where["post_id"] = $post_id;
        $where["content"] = $content;

        if ($from_uid != $to_uid){
            $res =  Db::name("message")->where($where)->find();
            if (!$res){
                Db::name("message")->insert($data);
            }
        }
    }

    public function imgCheck($img)
    {
        $img = file_get_contents($img);
        $filePath = 'uploads/tmp1.png';
        file_put_contents($filePath, $img);
        $obj = new \CURLFile(realpath($filePath));
        $obj->setMimeType("image/jpeg");
        $file['media'] = $obj;
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxa/img_sec_check?access_token=$token";
        $info = $this->http_request($url,$file);
        return json_decode($info,true)["errcode"];
    }
    
    public function pushTemplateMsg($data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=" .$this->getAccessToken();

        $res = curlData($url,json_encode($data,JSON_UNESCAPED_UNICODE),"POST");
        return json($res);
    }

    //HTTP请求（支持HTTP/HTTPS，支持GET/POST）
    private function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}