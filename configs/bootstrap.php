<?php
/*
|--------------------------------------------------------------------------
| 系统启动引导程序
|-------------------------------------------------------------------------- 
| Copyright (c)  All rights reserved.
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/
use Phalcon\Config as PhConfig;
use Phalcon\Loader as PhLoader;
use Phalcon\Mvc\Url as PhUrl;
use Phalcon\Mvc\View as PhView;
use Phalcon\Mvc\Application as PhApplication;
use Phalcon\Logger\Adapter\File as PhFileLogger;
use Phalcon\Logger\Formatter\Line as PhFormatterLine;
use Phalcon\Cache\Backend\File as BackFile;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Mvc\View\Engine\Volt as PhVolt;

class Bootstrap 
{
	private $di;

	/**
	 * 实例化类
	 * @author huzhichao502@gmail.com
	 * @param $di
	 */
	public function __construct() 
	{
		$this->di = new \Phalcon\DI\FactoryDefault();
	}

	/**
	 * 运行
	 * @access public
	 * @author huzhichao502@gmail.com
	 * @param array $options
	 * @return string
	 */
	public function run( $options = [] ): object
	{
		$loaders = [
			'Config', 	# 配置文件
			'Loader', 	# 设置自动加载路径
			'Router', 	# 加载路由
			'Database', # 初始化数据库服务
			'Cache',    # 初始化缓存
			'View', 	# 初始化视图服务
			'WebCurl',  # 初始化远程请求服务
			'WebRedis', # 初始化Redis 服务
			'WebPages', # 初始化页面服务
			'Logger'	# 初始化写日志服务
		];
		foreach ($loaders as $service) {
			$function = 'init' . $service;
			$this -> $function();
		}
		$application = new PhApplication();
		$application->setDI($this->di);
		return $application->handle()->getContent();
	}

	/**
	 * 初始化配置文件
	 * @access protected
	 * @author huzhichao502@gmail.com
	 *
	 * @param array $options
	 * @return void
	 */
	protected function initConfig ( $options = [] ): void
	{
		$this->di['config'] = (new PhConfig( require_once (ROOT_PATH . '/configs/inc_config.php') ?? []));
	}

	/**
	 * 初始化加载器
	 * @access protected
	 * @author huzhichao502@gmail.com
	 *
	 * @param array $options
	 * @return void
	 */
	protected function initLoader(  $options = [] ): void
	{
		$this->di['loader'] = (new PhLoader()) -> registerDirs([
			ROOT_PATH . '/app/controllers/', 
			ROOT_PATH . '/app/librarys/', 
			ROOT_PATH . '/app/models/',
			ROOT_PATH . '/app/services/'
		]) -> register();
	}

	/**
	 * 注册路由表
	 * @access protected
	 * @author huzhichao502@gmail.com
	 *
	 * @param array $options
	 * @return void
	 */
	protected function initRouter(  $options = [] ): void
	{
		$this->di['router'] = function() {
			return require_once (ROOT_PATH . '/configs/inc_routes.php');
		};
	}
	
	/**
	 * 注册数据库访问服务
	 */
	protected function initDatabase( $options = [] ): void
	{
		$config = $this->di['config'];
		$this->di['db'] = function() use ( $config ) {
			return new \Phalcon\Db\Adapter\Pdo\Mysql([
        		'host'       => $config->database->host,
        		'username'   => $config->database->username,
        		'password'   => $config->database->password,
        		'charset'    => 'UTF8',
        		'dbname'     => $config->database->dbname,
        		'persistent' => true
    		]);
		};
	}

	/**
	 * cache
	 * @access protected
	 * @author huzhichao502@gmail.com
	 *
	 * @param  array $options
	 * @return void
	 */
	protected function initCache( $options = [] ): void
	{
		$config = $this->di['config'];
		$this->di['cache'] = function() use ( $config ) {
			return new BackFile(new FrontData(array('lifetime' => $config->application->lifetime)), [
        		'prefix'   => $config->application->cache_prefix,
        		'cacheDir' => $config->application->path_cache,
    		]);
		};
	}
	
	/**
	 * 注册视图服务
	 * @access protected
	 * @author huzhichao502@gmail.com
	 *
	 * @param array $options
	 * @return void
	 */
	protected function initView( $options = [] ): void
	{
		$di = $this->di;
		$config = $this->di['config'];
		$this->di['view'] = function() use ( $config, $di ) {
			$view = new PhView();
			$view -> setViewsDir($config -> application -> path_views);
			$view -> registerEngines([
				'.phtml' => function($view, $di) use ( $config ) {
					$volt = new PhVolt($view, $di);
					$voltOptions = [
						'compiledPath' => $config -> application -> path_volt, 
						'compiledSeparator' => '_', 
					];
					if ( true == $config -> application -> debug) {
						$voltOptions['compileAlways'] = true;
					}
					$volt -> setOptions($voltOptions);
					return $volt;
				}
			]);
			return $view;
		};
	}



	/**
	 *  初始化WebCurl 服务
	 * @access protected
	 * @author huzhichao502@gmail.com
	 *
	 * @param array $options 需要传值的数组对象
	 * @return void
	 */
	protected function initWebCurl( $options = [] ): void
	{
		$di = $this->di;
		$this->di['curl'] = function() use ( $di ) {
			return new WebCurl();
		};
	}

	
	/**
	 *  初始化Redis 服务
	 * @access protected
	 * @author huzhichao502@gmail.com
	 *
	 * @param array $options 需要传值的数组对象
	 * @return void
	 */
	protected function initWebRedis( $options = [] ): void
	{
		$di = $this->di;
		$this->di['redis'] = function() use ($di) {
			return new WebRedis();
		};
	}


	/**
	 *  初始化Pages 服务
	 * @access protected
	 * @author huzhichao502@gmail.com
	 *
	 * @param array $options 需要传值的数组对象
	 * @return void
	 */
	protected function initWebPages( $options = [] ): void
	{
		$di = $this->di;
		$this->di['pages'] = function() use ($di) {
			return new WebPages();
		};
	}
	
	/**
	 *  配置写日志服务
	 * @access protected
	 * @author huzhichao502@gmail.com
	 *
	 * @param array $options 需要传值的数组对象
	 * @return void
	 */
	protected function initLogger( $options = [] ): void
	{
		$config = $this->di['config'];
		$this->di -> set('logger', 
		function($filename = null, $format = null) use ($config) {
			$format = $format ? : $config -> get('logger') -> format;
			$filename = trim($filename ? : $config -> get('logger') -> filename, '\\/');
			$path = rtrim($config -> get('logger') -> path, '\\/') . DIRECTORY_SEPARATOR;
			$formatter = new PhFormatterLine($format, $config -> get('logger') -> date);
			$logger = new PhFileLogger($path . $filename);
			$logger -> setFormatter($formatter);
			$logger -> setLogLevel($config -> get('logger') -> logLevel);
			return $logger;
		});
	}
}
