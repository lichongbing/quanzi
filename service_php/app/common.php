<?php

use PHPMailer\PHPMailer;
use think\facade\Db;
use OSS\OssClient;
use OSS\Core\OssException;


/**
 *
 */
function alYunOSS($filePath,$Extension){

    $alYunOSS = Db::name("system")->where("config","uploadType")->value("extend");
    $alYunOSS = json_decode( $alYunOSS,true);

    $accessKeyId = $alYunOSS["accessKeyId"];
    $accessKeySecret = $alYunOSS["accessKeySecret"];
    $endpoint = $alYunOSS["endpoint"];
    $bucket= $alYunOSS["OssName"];    // 存储空间名称
    $object = date("Ymd") .time() .rand(10000,99999) ."." .$Extension;    // 文件名称

    try{
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint,true);

        $rel = $ossClient->uploadFile($bucket, $object, $filePath);
        return ["code" => 1,"url" =>$rel["info"]["url"]];
    } catch(OssException $e) {
        return ["code" => 0,"msg" => $e->getMessage()];
    }
}
/**
 * @param $toemial 用户注册邮箱
 * @param string $title  激活邮件标题
 * @param $account 用户账号
 * @param $url 激活地址
 */
function sendEmail($toEmial,$title,$content){
    $mail = new PHPMailer(true);
    $SmtpInfo = Db::name("system")->where("config","smtp")->find();
    try {
        //Server settings
        $mail->SMTPDebug = 0;                                 // 调试模式 0 ，1 ，2
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.sina.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $SmtpInfo["value"];                 // SMTP username
        $mail->Password = $SmtpInfo["extend"];                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $SmtpInfo["intro"];                                    // TCP port to connect to 25 or 587
        $mail->CharSet = 'UTF-8';//邮件编码的设置
        //Recipients
        $mail->setFrom('mmteen@sina.com', '天年网络');
        $mail->addAddress($toEmial);               // Name is optional
        $mail->addReplyTo('mmteen@sina.com', '天年网络');

        //Content
        $mail->isHTML(true);                              // Set email format to HTML
        $mail->Subject = $title;
        $mail->Body    = $content;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function curlData($url, $data=null,$method='GET',$headers=array(), $https=true )
{
    // 创建一个新cURL资源
    $ch = curl_init();
    // 设置URL和相应的选项

    curl_setopt($ch, CURLOPT_URL, $url);
    //要访问的网站 //启用时会将头文件的信息作为数据流输出。
    curl_setopt($ch, CURLOPT_HEADER, false);
    //将curl_exec()获取的信息以字符串返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if($https){

        //FALSE 禁止 cURL 验证对等证书（peer's certificate）。
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //验证主机 }
        if($method == 'POST'){
            curl_setopt($ch, CURLOPT_POST, true);

            //发送 POST 请求  //全部数据使用HTTP协议中的 "POST" 操作来发送。
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        // 抓取URL并把它传递给浏览器
        $content = curl_exec($ch);
        //关闭cURL资源，并且释放系统资源
        curl_close($ch);

        return json_decode($content,true);

    }
}

function xmlToArray($xml)
{
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $values;
}

function isEmail($email){
    $pattern="/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
    if(preg_match($pattern,$email)){
        return true;
    } else{
       return false;
    }
}


//检测域名格式
function checkUrl($C_url)
{
    $str = "/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/";
    if (!preg_match($str, $C_url)) {
        return false;
    } else {
        return true;
    }
}
/**
 * 解析sql语句
 * @param  string $content sql内容
 * @param  int $limit  如果为1，则只返回一条sql语句，默认返回所有
 * @param  array $prefix 替换表前缀
 * @return array|string 除去注释之后的sql语句数组或一条语句
 */
function parse_sql($sql = '', $limit = 0, $prefix = []) {
    // 被替换的前缀
    $from = '';
    // 要替换的前缀
    $to = '';

    // 替换表前缀
    if (!empty($prefix)) {
        $to   = current($prefix);
        $from = current(array_flip($prefix));
    }

    if ($sql != '') {
        // 纯sql内容
        $pure_sql = [];

        // 多行注释标记
        $comment = false;

        // 按行分割，兼容多个平台
        $sql = str_replace(["\r\n", "\r"], "\n", $sql);
        $sql = explode("\n", trim($sql));

        // 循环处理每一行
        foreach ($sql as $key => $line) {
            // 跳过空行
            if ($line == '') {
                continue;
            }

            // 跳过以#或者--开头的单行注释
            if (preg_match("/^(#|--)/", $line)) {
                continue;
            }

            // 跳过以/**/包裹起来的单行注释
            if (preg_match("/^\/\*(.*?)\*\//", $line)) {
                continue;
            }

            // 多行注释开始
            if (substr($line, 0, 2) == '/*') {
                $comment = true;
                continue;
            }

            // 多行注释结束
            if (substr($line, -2) == '*/') {
                $comment = false;
                continue;
            }

            // 多行注释没有结束，继续跳过
            if ($comment) {
                continue;
            }

            // 替换表前缀
            if ($from != '') {
                $line = str_replace('`'.$from, '`'.$to, $line);
            }
            if ($line == 'BEGIN;' || $line =='COMMIT;') {
                continue;
            }
            // sql语句
            array_push($pure_sql, $line);
        }

        // 只返回一条语句
        if ($limit == 1) {
            return implode($pure_sql, "");
        }

        // 以数组形式返回sql语句
        $pure_sql = implode($pure_sql, "\n");
        $pure_sql = explode(";\n", $pure_sql);
        return $pure_sql;
    } else {
        return $limit == 1 ? '' : [];
    }
}