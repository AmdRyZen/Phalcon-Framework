<?php
// +----------------------------------------------------------------------
// | 配置文件 参数配置
// +----------------------------------------------------------------------
// | Copyright (c) 2017--20xx年 捞月狗. All rights reserved.
// +----------------------------------------------------------------------
// | File:     inc_config.php
// |
// | Author:   huzhichao@laoyuegou.com
// | Created:  2017-xx-xx
// +----------------------------------------------------------------------
use Phalcon\Logger;

$config = [
    'application' => [
        'path_views'        => ROOT_PATH . '/app/views/',
        'path_volt'         => ROOT_PATH . '/runtime/volt/',
    	'path_public'       => ROOT_PATH . '/public/',
        'path_cache'        => ROOT_PATH . '/runtime/cache/',
        'debug'             => '0',
        'lifetime'          => 10,
        'cache_prefix'      => 'phalcon',
        'js_css_version'    => '',
        'keywords'          => '',
        'description'       => '',
        'suffix'            => '队列消耗',
        'description'       => '',
    ],
    'database' => [ // cmf 主库
        'adapter'  => 'Mysql',
        'host'     => '127.0.0.1',
        'username' => 'root',
        'password' => 'root',
        'dbname'   => 'thinkcmf5'
    ],
    'database_1' => [ // 文章发布库
        'adapter'  => 'Mysql',
        'host'     => '127.0.0.1',
        'username' => 'root',
        'password' => 'root',
        'dbname'   => 'thinkcmf5'
    ],
    'redis' => [
        'host'          => '127.0.0.1',
        'port'   		=> 6379,
        'auth_password' => '',
        "persistent"    => false,
        'lifttime'      => 86400
    ],
    'autoload'=>[
       'path_tasks'     => ROOT_PATH . '/app/tasks/',
       'path_librarys'  => ROOT_PATH . '/app/librarys/',
       'path_services'  => ROOT_PATH . '/app/services/'
    ],
    'logger' => [
        'path'     => ROOT_PATH . '/runtime/logs/',
        'format'   => '%date% [%type%] %message%',
        'date'     => 'H:i:s',
        'logLevel' => Logger::DEBUG,
        'filename' => 'apps-'.date('Y-m-d') .'.log',
    ],
    'upload' => [
        'prefix'        => 'language',
        'mimes'         =>  ['xlsx','xls','doc'], //允许上传的文件MiMe类型
        'maxSize'       =>  1048576, //上传的文件大小限制 (0-不做限制 1048576:1M大小)
        'exts'          =>  ['xlsx','xls','doc'], //允许上传的文件后缀
        'rootPath'      =>  '/public/uploads/' //保存根路径
    ],
    'headers' => [
        'Developer' 	=> 'phalcon',
        'X_Powered_By' 	=> 'Multi Concise Framework 3.4',
        'Server' 		=> 'Leopard Server 10',
        'Content_Type' 	=> 'application/json;charset=utf-8',
        'Status_Code' 	=> 'okey!'
    ]
];
return $config;