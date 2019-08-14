<?php
/*
|--------------------------------------------------------------------------
| 基础服务类
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/
use Phalcon\Mvc\User\Component;

class BaseService extends Component {
	public $_NODATA = 'NODATA';
	protected $sdb = null;

    /**
    * 数据库name 读取配置文件
    *
    * @var array
    */
	protected function select_db( string $name ): void
	{
		if (empty($name))
			$name = 'database';
		$db = $this->config->$name;

		$config = [
        		'host'     => $db->host,
        		'username' => $db->username,
        		'password' => $db->password,
        		'charset'  => 'UTF8',
        		'dbname'   => $db->dbname
    	];
		$this->sdb = new \Phalcon\Db\Adapter\Pdo\Mysql($config);
		$this->sdb->connect();
	}

    /**
    * 数据库配置文件 直接连接
    *
    * @var array
    */
	protected function selects_db( array $db ): void
	{
		$config = [
        		'host'     => $db['db_host'] ?? '',
        		'username' => $db['db_user_name'] ?? '',
        		'password' => $db['db_user_pwd'] ?? '',
        		'charset'  => 'UTF8',
        		'dbname'   => $db['db_name'] ?? ''
    	];
		$this->sdb = new \Phalcon\Db\Adapter\Pdo\Mysql($config);
		$this->sdb->connect();
	}
}
