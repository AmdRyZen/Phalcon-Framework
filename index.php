<?php
/*
|--------------------------------------------------------------------------
| Application Name
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/
define('APP_NAME', 'Phalcon');

date_default_timezone_set('Asia/Shanghai');

/**
 * Phalcon - A PHP Framework For Web Artisans
 *
 * @package  Phalcon
 * @author   Taylor Otwell <taylor@Phalcon.com>
 */
define('PHALCON_START', microtime(true));


define('ROOT_PATH', dirname(__FILE__));


// 应用程序 ///////////////////////////////////////////////////////////
try {
	header('Content-Type: application/json; charset=utf-8');

	error_reporting(E_ALL);

	require_once ROOT_PATH . "/configs/bootstrap.php";

	(new Bootstrap())->run([]);
} catch (\Phalcon\Exception $e) {
	echo "<pre>"; print_r( $e->getMessage() );exit;
}
