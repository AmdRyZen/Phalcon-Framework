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


class ArticleReleaseService extends BaseService
{
	public function release() {

		$sql = 'select * from cmf_platform where platform_id = 1 and status = 0 and num < 60';
		$db_list = $this->db->fetchAll($sql);

		foreach ($db_list as $key => $value) {

			$this->selects_db( $value ); // 选择数据库

			$sql = "select * from cmf_article";
			$res = $this->sdb->fetchAll($sql);

			print_r($res);

			# code...
		}
		
		
	}
	
}