<?php
// +----------------------------------------------------------------------
// | API控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2017--20xx年 捞月狗. All rights reserved.
// +----------------------------------------------------------------------
// | File:     api.php
// |
// | Author:   huzhichao@laoyuegou.com
// | Created:  2017-xx-xx
// +----------------------------------------------------------------------
use Phalcon\Translate\Adapter\NativeArray;
//use PHPExcel;

class ApiController extends BaseController 
{

	public function testAction( $id = 0 ) 
	{
		//echo "<pre>";print_r( phpinfo() );exit;
		$yac = new \Yac();
		$key = 'key';
		$yac->set( $key, ['id' => 111111]);

		$ret = $yac->get($key);

		//echo "<pre>";print_r( $yac->info() );exit;

        return $this->success(["ret" => $ret]);
    }

}
