<?php
/*
|--------------------------------------------------------------------------
| 用户文章生成
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/


class ArticleService extends BaseService
{
	public static $key = 'userlist';

	public static $num = 500; // 每次处理多少条数据

	public function article() {

		$list = $this->rpop();
		
		if( empty($list) )
			throw new Exception("redis 列表中有空数据，请检查！", 1);

		if( !is_array($list) )
			throw new Exception("redis 列表数据有异常，请检查！", 2);
			

		$a = array_slice($list, 0, self::$num); //取数组前多少条

		$b = array_slice($list, self::$num, count($list) - 1 );


		//  2:2470:11,13,16:19,21,24:8：3,4,5   用户ID：标题ID：段落ID：句子ID：关键词ID(kid cmf_key_image) ： 组合标题规则
		try {
			foreach ($a as $k => $v) {
				
				$this->db->begin();

				$arr = explode(':', $v);

				$picid = $this->getPicid(  intval($arr[4]) );

				$platform_id = $this->platformId();

				$sql = "INSERT INTO `cmf_article` (
						`uid`,
					    `title`,
						`section`,
						`sentence`,
						`kid`,
						`picid`,
						`platform_id`,
						`com_title`) VALUES (
						:uid,
						:title,
						:section,
						:sentence,
						:kid,
						:picid,
						:platform_id,
						:com_title
						) ";

				if(!$this->db->execute($sql, [
					'uid'      => $arr[0],
					'title'    => $arr[1],
					'section'  => $arr[2],
					'sentence' => $arr[3],
					'kid'      => $arr[4],
					'picid'    => $picid,
					'platform_id'  => $platform_id,
					'com_title'    => $arr[5],
				])) {
					array_push( $b , $v );
					throw new Exception("数据 {$v} 插入失败，重新进入队列循环！", 3);
				}

				$this->db->commit();
				# code...
			}
		} catch (Exception $e) {
			$this->db->rollback();
		}

		if( !empty($b) ) { //剩余的继续塞进队列
			$this->lpush( $b );
		}	

	}
    
    /**
    * 随机获取平台ID
    *
    * @var array
    */
	public function platformId() {
		return 999;

	}

	/**
	* getPicid
	*
	* @var string
	*/
	public function getPicid( int $kid ) {

		$sql = "SELECT picid FROM `cmf_key_image` WHERE `kid` = $kid";
		$res = $this->db->fetchOne($sql);
		return $res['picid'] ?? '0';
	}

    /**
    * 从队列拿数据
    *
    * @var array
    */
	public function rpop() {
		$list = $this->redis->rpop(self::$key,'o');
		@$list = json_decode( $list , true );
		return $list;
	}

	/**
	* 向队列压数据
	*
	* @var array
	*/
	public function lpush( $arr ) {
		if(is_array( $arr ))
			$ret = $this->redis->lpush( self::$key, json_encode($arr), 'o');

	}
	
}