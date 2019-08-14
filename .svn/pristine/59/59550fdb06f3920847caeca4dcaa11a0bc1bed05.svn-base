<?php
/*
|--------------------------------------------------------------------------
| 用户文章
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/
class ArticleTask extends \Phalcon\Cli\Task
{

	/**
	* 用户文章生成 （消耗Redis 队列写入cmf_article）
	*
	* @var array
	*/
	public function mainAction(array $params)
	{
		try{

			$a_time = microtime(true);
	
			$service = new ArticleService( );

			$service->article( );

			$e_time = microtime(true);

			$time =  $e_time - $a_time;   // 执行时间

			$num = ArticleService::$num;  // 执行条数

			$this->logger->info("文章生成 {$num} 条的时间----" . $time);
		
		}catch (\Exception $e) {
		    $this->logger->error($e->getMessage());
		}
	}

	/**
	* 用户文章发布
	*
	* @var array
	*/
	public function releaseAction(array $params)
	{
		try{

			$service = new ArticleReleaseService( );

			$service->release();

			$e_time = microtime(true);


			//$this->logger->info("文章 {$num} 条的时间----" . $time);
		
		}catch (\Exception $e) {
		    $this->logger->error($e->getMessage());
		}

	}

}