<?php
/*
|--------------------------------------------------------------------------
| Task
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/
ini_set ('memory_limit', '2048M');
use Phalcon\DI\FactoryDefault\CLI as CliDI,
    Phalcon\CLI\Console as ConsoleApp,
	Phalcon\Config as PhConfig;

error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

define('VERSION', '1.0.0');
// 定义应用目录路径
defined('ROOT_PATH') || define('ROOT_PATH', __DIR__);

// 使用CLI工厂类作为默认的服务容器
$di = new CliDI();

//加载配置文件////////////////////////////////////////////////////////////////////////////////////////////////////////
$data = require_once (ROOT_PATH . '/configs/inc_config.php');
$config = new PhConfig($data);
$di -> set('config', $config);

//注册类自动加载器////////////////////////////////////////////////////////////////////////////////////////////////////////
$loader = new \Phalcon\Loader();
$loader->registerDirs($data['autoload']);
$loader->register();

//数据库服务///////////////////////////////////////////////////////////////////////////////////////////////
$di->set('db', function () use ( $config ) {
    return new Phalcon\Db\Adapter\Pdo\Mysql([
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'charset'  => 'UTF8',
        'dbname'   => $config->database->dbname
    ]);
});

$di->set('db1', function () use ( $config ) {
    return new Phalcon\Db\Adapter\Pdo\Mysql([
        'host'     => $config->database_1->host,
        'username' => $config->database_1->username,
        'password' => $config->database_1->password,
        'charset'  => 'UTF8',
        'dbname'   => $config->database_1->dbname
    ]);
});

// 公共的函数库 Common 服务///////////////////////////////////////////////////////////////////////////////////////////////
$di->set('util', function () use ( $config ) {
    return new Utilitys();
});

// Redis 服务///////////////////////////////////////////////////////////////////////////////////////////////
$di->set('redis', function () use ( $config ) {
    return new WebRedis();
});

//日志服务///////////////////////////////////////////////////////////////////////////////////////////////
$di -> set('logger', function($filename='') use ($config) {
	$format   = $config->get('logger')->format;
    $filename = trim($config->get('logger')->filename, '\\/');
	$path = rtrim($config -> get('logger') -> path, '\\/') . DIRECTORY_SEPARATOR;
	$formatter = new Phalcon\Logger\Formatter\Line($format, $config -> get('logger') -> date);
	$logger = new Phalcon\Logger\Adapter\File($path . $filename);
	$logger -> setFormatter($formatter);
	return $logger;
});

//创建console应用////////////////////////////////////////////////////////////////////////////////////////////////////////
$console = new ConsoleApp();
$console -> setDI($di);

//处理console应用参数  ////////////////////////////////////////////////////////////////////////////////////////////////////////
$arguments = [];
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

//定义全局的参数， 设定当前任务及动作  /////////////////////////////////////////////////////////////////////////////////////////////////////
define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

// 处理参数   /////////////////////////////////////////////////////////////////////////////////////////////////////
try {
     $console->handle($arguments);
	 exit(0);
} catch (\Phalcon\Exception $e) {
	 $dt = date('Y-m-d H:i:s');
	 $di->get('logger')->error("错误：[{$dt}] {$e->getMessage()}\n\n");
	 echo "错误：[{$dt}] {$e->getMessage()}\n\n";
     exit(255);
}
