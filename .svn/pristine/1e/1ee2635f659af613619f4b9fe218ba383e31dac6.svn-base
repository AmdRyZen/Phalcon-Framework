<?php
// +----------------------------------------------------------------------
// | MainTask
// +----------------------------------------------------------------------
// | Copyright (c) 2017--20xx年 捞月狗. All rights reserved.
// +----------------------------------------------------------------------
// | File:     MainTask.php
// |
// | Author:   huzhichao@laoyuegou.com
// | Created:  2017-xx-xx
// +----------------------------------------------------------------------
class MainTask extends \Phalcon\Cli\Task
{
	/**
	* test
	* @return {json}
	* @param  
	* @author huzhichao@laoyuegou.com
	* @link   
	* @date   
	*/
	public function mainAction(array $params)
	{
		try{
			echo 'Welcome to you';
			
			$this->logger->info('Welcome to you');
		
		}catch (\Exception $e) {
		    $this->logger->error($e->getMessage());
		}
	}

}