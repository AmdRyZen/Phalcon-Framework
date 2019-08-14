<?php
/*
|--------------------------------------------------------------------------
| WebRedis
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/
use Phalcon\Mvc\User\Component;

class WebRedis  extends Component {

	public $database = array(
		'all'=>0,
		
		'a'=>1,

		'b'=>2,

		'c'=>3,

		'd'=>4,

		'e'=>5,

		'f'=>6,

		'g'=>7,

		'h'=>8,

		'i'=>9,

		'j'=>10,

		'k'=>11,

		'l'=>12,
	
		'm'=>13,

		'n'=>14,   // taglist
		
		'o'=>15    // 文章生成规则
	);

	public $_redis = null;

	/**
	 * 连接缓存数据库
	 * @author 
	 * @return void
	 */
	public function connect($db='') {
		if (!isset($this -> _redis)) {
			$conf = $this -> config -> redis;
			$this -> _redis = new Redis();
			$this -> _redis -> connect($conf -> host, $conf -> port);
			if (!empty($conf -> auth_password))
				$this -> _redis ->auth($conf -> auth_password);
			if (!empty($db))
				$this->select_db($db);
		}
	}

	/**
	 * 选择数据库
	 * @author 
	 * @return void
	 */
	protected function select_db($db) {
		if (isset($this->_redis))
			$this -> _redis -> select(intval($this -> database[$db]));
	}


	/**
    * 集合 sadd
    *
    * @var string
    */
    public function sadd( $key, $value, $db = '' ) {
    	$this -> connect($db);
    	return  $this -> _redis -> sadd( $key, $value );

    }

    /**
    * 集合 smembers
    *
    * @var array
    */
    public function smembers( $key, $db = '' ) {
    	$this -> connect($db);
    	return  $this -> _redis -> smembers( $key );

    }

    /**
    * 集合 zadd
    *
    * @var string
    */
    public function zadd( $key, $score, $value, $db = '' ) {
    	$this -> connect($db);
    	return  $this -> _redis -> zadd( $key,  $score, json_encode($value) );

    }
    
    /**
    *  获取成员个数
    *
    * @var array
    */
    public function zcard( $key, $db ) {
    	$this -> connect($db);
    	return  $this -> _redis -> zcard( $key );
    }

    /**
	*  获取制定范围的元素 　zrange key start end
	*
	* @var array
	*/
	public function zrange( string $key, int $start,  int $end, string $db )
	{
		$this -> connect($db);
    	return  $this -> _redis -> zrange( $key, $start, $end );
	}


	/**
	 * 判断redis 是否连通
	 */
	protected function is_pang() {
		$pattern = '/PONG/';
		$ping = $this -> _redis -> ping();
		return (preg_match($pattern, $ping));
	}

	/**
	 * 写入数据
	 */
	public function write($key,$data,$db,$lifetime=0) {
		$this -> connect($db,'w');
		$this -> _redis -> multi();
		if (is_array($data))
			$info = json_encode($data);
		else{
			$info = $data;
		}

		if (empty($info))
			$this->logger->error($info);

		$this -> _redis -> set($key, $info);
		if ( $lifetime > 0 )
			$this->_redis->setTimeout($key,$lifetime);
		// 执行事务
		$this -> _redis -> exec();
	}

	/**
	 * 删除数据
	 * @author 
	 * @param $db string 数据库编号
	 * @param $key string 键名
	 *
	 * @return int
	 */
	public function remove($key,$db)
	{
		$this -> connect($db,'w');
		if ($this -> _redis -> exists($key)) {
			return $this -> _redis -> del($key);
		}
		return 0;
	}


	/**
	* 同时删除多个Keys
	* @param keys , db
	* @author 
	* @date 
	*/
	public function removeMulti($keys, $db)
	{
		$this -> connect($db,'w');
		$this->_redis->del($keys);
		return 0;
	}


	/**
	* 查询指定范围内的元素列表　　lrange key start end　　
	*
	* @var array
	*/
	public function lrange( string $key, int $start,  int $end, string $db )
	{
		$this -> connect($db);
    	return  $this -> _redis -> lrange( $key, $start, $end );
	}

	/**
    * 获取列表长度
    *
    * @var string
    */
    public function llen( $key, $db = '' ) {
    	$this -> connect($db);
    	return  $this -> _redis -> llen($key);

    }
    
    /**
    * 从list右边取一条
    *
    * @var string
    */
    public function rpop( $key, $db = '' ) {
    	$this -> connect($db);
    	return  $this -> _redis -> rpop($key);

    }

    /**
    * 从list左边压一条
    *
    * @var bool
    */
    public function lpush( $key, $value, $db = '' ) {
    	$this -> connect($db);
    	return  $this -> _redis -> lpush( $key, $value );

    }

     /**
    * 从list右边压一条
    *
    * @var bool
    */
    public function rpush( $key, $value, $db = '' ) {
    	$this -> connect($db);
    	return  $this -> _redis -> rpush( $key, $value );

    }

	/**
	 * 读取数据
	 * @author 
	 * @param $key 关键字
	 * @param $db 数据库标识
	 * @param $type 类型 0:不做任何处理 1：string 2:hash 3:list 4:set 5:zset
	 */
	public function read($key,$db,$type=1) {
		$data = null;
		$this -> connect($db);
		if ($this -> _redis -> exists($key)) {
			$value = '';
			switch ($type) {
				case 0:
					$data = $this -> _redis -> get($key);
					break;
				case 1:
					$value = $this -> _redis -> get($key);
					if (!empty($value) && $value!='NODATA')
						$data = json_decode($value,TRUE);
					else {
						$data = 'NODATA';
					}
					break;
				case 2:
					$data = $this -> _redis -> hGetAll($key);
					break;
			}
		}
		return $data;
	}


    /**
    * 读取多条数据
    * @return {json}
    * @param  $keys
    * @param  $db 数据库标识
    * @param  $type 类型： 0:不做任何处理 1：string 2:hash 3:list 4:set 5:zset 
    * @author huzhichao@laoyuegou.com
    * @link   
    * @date   
    */
	public function read_multi($keys,$db,$type=1) {
		$data = null;
		$this-> connect($db);
		if(is_array($keys) && !empty($keys)){
			$data = $this-> _redis ->getMultiple($keys);
		}
		return $data;
	}

	public function incr($key,$db){
		$this->connect($db,'w');
		return $this -> _redis->incr($key);
	}

	//加上指定数量
	public function incrBy($key,$db,$increment){
		$this->connect($db,'w');
		return $this -> _redis->incrBy($key,$increment);
	}

	public function decr($key,$db){
		$this->connect($db,'w');
		return $this -> _redis->decr($key);
	}

	public function decrBy($key,$db,$decrement){
		$this->connect($db,'w');
		return $this -> _redis->incrBy($key,$decrement);
	}

	public function exists($key,$db)
	{
		$this->connect($db);
		return $this -> _redis -> exists($key);
	}

}
