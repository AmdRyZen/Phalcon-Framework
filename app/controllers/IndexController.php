<?php
// +----------------------------------------------------------------------
// | IndexController
// +----------------------------------------------------------------------
// | Copyright (c) 2017--20xx年 捞月狗. All rights reserved.
// +----------------------------------------------------------------------
// | File:     index.php
// |
// | Author:   huzhichao@laoyuegou.com
// | Created:  2017-xx-xx
// +----------------------------------------------------------------------

class IndexController extends BaseController {

	public function indexAction()
	{
		return $this->success( 'Welcome to you' );
	}

	/**
	 * 返回 400 信息
	 */
	public function badRequestAction( $msg = '' ) {
		return $this->failure( $msg, 400 );
	}

	/**
	 * 返回 401 未授权 信息 （检查 APPID与APPKEY）
	 */
	public function unauthorizedAction( $msg = '' ) {
		return $this->failure( $msg,401 );
	}

	/**
	 * 返回 403 无权访问访问 (检查 Token)
	 */
	public function forbiddenAction( $msg = '') {
		return $this->failure( $msg, 403 );
	}

	/**
	 * 返回 404 资源找不到
	 */
	public function notFoundAction( $msg = '' ) {
		return $this->failure( $msg, 404 );
	}

	/**
	 * 返回 405 方法错误 不允许使用请求行中所指定的方法
	 */
	public function notAllowedAction( $msg = '') {
		return $this->failure( $msg, 405 );
	}

	/**
	 * 返回 500 服务器出错信息
	 */
	public function serverErrorAction( $msg = '') {
		return $this->failure( $msg, 500 );
	}
}
