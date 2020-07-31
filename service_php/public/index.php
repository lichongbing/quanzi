<?php
// [ 应用入口文件 ]
namespace think;

header('Content-Type:text/html;charset=utf-8');
header("Access-Control-Allow-Origin: *");

// 检测PHP环境
if(version_compare(PHP_VERSION,'7.1.0','<'))  die('PHP版本过低，最少需要PHP7.1.0，请升级PHP版本！');

require __DIR__ . '/../vendor/autoload.php';

//定义系统版本
require __DIR__ . '/version.php';

$http = (new  App())->http;

// 检测程序安装
if(!is_file(__DIR__ . '/install.lock')){
    $response = $http->name('install')->run();
}else{
    $response = $http->run();
}

// 执行HTTP应用并响应
$response->send();
$http->end($response);