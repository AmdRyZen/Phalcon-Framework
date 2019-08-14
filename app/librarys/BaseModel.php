<?php
/*
|--------------------------------------------------------------------------
| 基础模型类
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/
use Phalcon\Mvc\User\Component;

class BaseModel extends Component {
	public $_NODATA = 'NODATA';
	protected $sdb = null;

	protected function select_db($name)
	{
		if (empty($name))
			$name = 'database';
		$db = $this->config->$name;

		$config = array(
        		'host' => $db->host,
        		'username' => $db->username,
        		'password' => $db->password,
        		'charset' => 'UTF8',
        		'dbname' => $db->dbname
    	);
		$this->sdb = new \Phalcon\Db\Adapter\Pdo\Mysql($config);
		$this->sdb->connect();
	}

}
