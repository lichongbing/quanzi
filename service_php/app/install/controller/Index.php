<?php


namespace app\install\controller;


use think\facade\Db;
use think\facade\View;
class Index
{

    public function index()
    {
        return View::fetch("/install");
    }

    public function dbInstall()
    {
        $data = input("post.");

        if (!$this->mkDatabase($data)){
            return json(["code" => 0,"msg" => "数据库配置文件创建失败"]);
        }

        // 创建数据库连接
        $servername = "localhost";
        $username =  $data["db_user_name"];
        $password = $data["db_user_pwd"];
        $dbname = $data["db_name"];

        try {
            $conn = mysqli_connect($servername, $username, $password,$dbname);

            $sqlFile = realpath(root_path().'/app/install/database.sql');
            if (is_file($sqlFile)) {
                $sql = file_get_contents($sqlFile);
                $sqlList = parse_sql($sql, 0, ['ym_' => $data["db_prefix"]]);

                if ($sqlList) {
                    $sqlList = array_filter($sqlList);

                    foreach ($sqlList as $v) {
                        try {
                            $conn->query($v);
                        } catch (\Exception $e) {
                            return json(["code" => 0, "msg" => $e->getMessage()]);
                        }
                    }
                }
            }
        }catch (\Exception $e){
            return json(["code" => 0,"msg" => "数据库连接失败，请检查数据库配置！"]);
        }

        $table = $data["db_prefix"] ."user";
        $sql = 'INSERT INTO '.$table.' (username, password, group_id)VALUES("'.$data["username"].'","'.md5($data["password"]).'",1)';

        $createAdminUser =  $conn->query($sql);

        if ($createAdminUser){
            file_put_contents(root_path().'/public/install.lock', "如需重新安装，请手动删除此文件\n安装时间：".date('Y-m-d H:i:s'));
            return json(["code" => 1,"msg" => "安装成功，已成功创建管理员账号"]);
        }
        return json(["code" => 0,"msg" => "安装失败"]);
    }

    /**
     * 生成数据库配置文件
     * @return array
     */
    private function mkDatabase(array $data)
    {
        $code = <<<INFO
<?php

use think \ facade\Env;

return [
    // 默认使用的数据库连接配置
    'default'         => Env::get('database.driver', 'mysql'),

    // 自定义时间查询规则
    'time_query_rule' => [],

    // 自动写入时间戳字段
    // true为自动识别类型 false关闭
    // 字符串则明确指定时间字段类型 支持 int timestamp datetime date
    'auto_timestamp'  => true,

    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y年m月d日',

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'              => Env::get('database.type', 'mysql'),
            // 服务器地址
            'hostname'          => Env::get('database.hostname', '127.0.0.1'),
            // 数据库名
            'database'          => Env::get('database.database', '{$data["db_name"]}'),
            // 用户名
            'username'          => Env::get('database.username', '{$data["db_user_name"]}'),
            // 密码
            'password'          => Env::get('database.password', '{$data["db_user_pwd"]}'),
            // 端口
            'hostport'          => Env::get('database.hostport', '3306'),
            // 数据库连接参数
            'params'            => [],
            // 数据库编码默认采用utf8
            'charset'           => Env::get('database.charset', 'utf8'),
            // 数据库表前缀
            'prefix'            => Env::get('database.prefix', '{$data["db_prefix"]}'),
            // 数据库调试模式
            'debug'             => Env::get('database.debug', true),
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'            => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'       => false,
            // 读写分离后 主服务器数量
            'master_num'        => 1,
            // 指定从服务器序号
            'slave_no'          => '',
            // 是否严格检查字段是否存在
            'fields_strict'     => true,
            // 是否需要断线重连
            'break_reconnect'   => false,
            // 字段缓存路径
            'schema_cache_path' => app()->getRuntimePath() . 'schema' . DIRECTORY_SEPARATOR,
        ],

        // 更多的数据库配置信息
    ],
];

INFO;

        file_put_contents(root_path().'config/database.php', $code);

        // 判断写入是否成功
        $config = include root_path() .'/config/database.php';

        $db_database = $config["connections"]["mysql"]["database"];
        $db_username = $config["connections"]["mysql"]["username"];
        $db_password = $config["connections"]["mysql"]["password"];
        $db_prefix = $config["connections"]["mysql"]["prefix"];

        if ($db_database != $data["db_name"] || $db_username != $data["db_user_name"] || $db_password != $data["db_user_pwd"] || $db_prefix != $data["db_prefix"]){
            return false;
        }

        return true;
    }
}