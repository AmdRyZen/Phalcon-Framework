<?php
/*
|--------------------------------------------------------------------------
| 常用操作类
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/
use Phalcon\Mvc\User\Component;

class Utilitys extends Component {

	//计算签名
	public function cal_signatue($str, $key) {
		$sign = "";
		if (function_exists("hash_hmac")) {
			$sign = base64_encode(hash_hmac("sha1", $str, $key, true));
		} else {
			$blockSize = 64;
			$hashfunc = "sha1";
			if (strlen($key) > $blockSize) {
				$key = pack('H*', $hashfunc($key));
			}
			$key = str_pad($key, $blockSize, chr(0x00));
			$ipad = str_repeat(chr(0x36), $blockSize);
			$opad = str_repeat(chr(0x5c), $blockSize);
			$hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc($key ^ $ipad) . $str)));
			$sign = base64_encode($hmac);
		}
		return $sign;
	}

	//计算时间戳
	public function microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

}
