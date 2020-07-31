<?php


namespace app\api\controller;


class Media extends  Base
{
    protected $secretId = "AKIDCE4qKfNmaBbpw88zhAPR2OHyR0MVxBA5";
    protected $secretKey = "t9NbcQ2PXVIuOgw5ze7ONIxPgiW6Vr8G";

    public function getMusicCate()
    {
        $secretId = $this->secretId;
        $secretKey = $this->secretKey;

        $Nonce = rand(10000,99999);
        $limit = 30;
        $param = [
            "Action" => "DescribeStations",
            "Limit" => $limit,
            "Nonce" => $Nonce,
            "Offset" => 0,
            "SecretId" => $secretId,
            "Timestamp" => time(),
            "Version" => "2019-09-16"
        ];

       ksort($param);

        $signStr = "GETame.tencentcloudapi.com/?";
        foreach ( $param as $key => $value ) {
            $signStr = $signStr . $key . "=" . $value . "&";
        }
        $signStr = substr($signStr, 0, -1);

        $signature = base64_encode(hash_hmac("sha1", $signStr, $secretKey, true));

        $url = "https://ame.tencentcloudapi.com/?Action=DescribeStations&Limit=".$limit."&Nonce=".$Nonce."&Offset=0&SecretId=".$secretId."&Signature=".$signature."&Timestamp=".time()."&Version=2019-09-16";

        $res = curlData($url);

        return json($res);
    }

    public function getMusicList()
    {
        $secretId = $this->secretId;
        $secretKey = $this->secretKey;

        $CategoryId = input("id");

        $Nonce = rand(10000,99999);
        $limit = 30;
        $param = [
            "Action" => "DescribeItems",
            "Limit" => $limit,
            "Nonce" => $Nonce,
            "Offset" => 0,
            "SecretId" => $secretId,
            "Timestamp" => time(),
            "CategoryId" => $CategoryId,
            "Version" => "2019-09-16"
        ];

        ksort($param);

        $signStr = "GETame.tencentcloudapi.com/?";
        foreach ( $param as $key => $value ) {
            $signStr = $signStr . $key . "=" . $value . "&";
        }
        $signStr = substr($signStr, 0, -1);

        $signature = base64_encode(hash_hmac("sha1", $signStr, $secretKey, true));

        $url = "https://ame.tencentcloudapi.com/?Action=DescribeItems&Limit=".$limit."&Nonce=".$Nonce."&Offset=0&SecretId=".$secretId."&Signature=".$signature."&Timestamp=".time()."&Version=2019-09-16&CategoryId=".$CategoryId;

        $res = curlData($url);

        return json($res);
    }

    public function getMusicInfo()
    {
        $id = input("id");

        $secretId = $this->secretId;
        $secretKey = $this->secretKey;

        $IdentityId = "user_".time();
        $Nonce = rand(10000,99999);
        $param = [
            "Action" => "DescribeMusic",
            "Nonce" => $Nonce,
            "SecretId" => $secretId,
            "Timestamp" => time(),
            "ItemId" => $id,
            "IdentityId" => $IdentityId,
            "Version" => "2019-09-16"
        ];

        ksort($param);

        $signStr = "GETame.tencentcloudapi.com/?";
        foreach ( $param as $key => $value ) {
            $signStr = $signStr . $key . "=" . $value . "&";
        }
        $signStr = substr($signStr, 0, -1);

        $signature = base64_encode(hash_hmac("sha1", $signStr, $secretKey, true));

        $url = "https://ame.tencentcloudapi.com/?Action=DescribeMusic&SecretId=".$secretId."&Nonce=".$Nonce."&Signature=".$signature."&IdentityId=".$IdentityId."&Timestamp=".time()."&Version=2019-09-16&ItemId=".$id;

        $res = curlData($url);

        return json($res);
    }
}