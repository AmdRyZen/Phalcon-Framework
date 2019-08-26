<?php
/*
|--------------------------------------------------------------------------
| 基类控制器
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/
use Phalcon\Mvc\Controller,
	Phalcon\Mvc\Dispatcher;

/**
 * 不需要验证的基类控制器
 */
class BaseController extends Controller {

	/**
	 * 执行路由之前的事件
	 * @access protected
	 * @author
	 * 
	 * @param $dispatcher 分发器
	 * @return void || bool
	 */
	/*protected function beforeExecuteRoute(Dispatcher $dispatcher) {
		
	}*/

	/**
	 * 模板变量赋值
	 * @access protected
	 * @param mixed $name 要显示的模板变量
	 * @param mixed $value 变量的值
	 * @return void
	 */
	protected function assign($name, $value = '') {
		$this -> view -> setVar($name, $value);
	}

	/**
	 * 模板显示 调用内置的模板引擎显示方法，
	 * @access protected
	 * @author 
	 *
	 * @param string $templateFile 	视图
	 * @param string $layout 		布局
	 * @return void
	 */
	protected function display($template, $layout = '') {
		if (empty($layout))
			$layout = $this -> config -> application -> layout;
		$this -> view -> setTemplateBefore($layout);
		$this -> view -> pick($template);
	}

	/**
	 * 设置布局文件
	 * @access protected
	 * @param mixed $name 要显示的布局文件
	 * @return void
	 */
	protected function layout($name) {
		if ($name) {
			$this -> view -> setLayout($name);
		}
	}

	/**
	 * 重定向到另一个地址
	 * @access protected
	 *
	 * @author
	 * @param $location 重定向的地址
	 */
	protected function redirect($location = '') {
		$this -> view -> disable();
		if (!empty($location)) {
			return $this -> response -> redirect($location, FALSE) -> send();
		}
	}

	/**
	 * 返回操作成功的JSON数据
	 * @access protected
	 * @author 
	 *
	 * @param string $msg   操作成功提示信息
	 * @param array  $data  返回的结果数据
	 * @param string $id    返回的相关编号
	 * @return
	 */
	protected function success($data = [], $msg = 'ok', $code = 200)
	{
		$result = ['success' => true, 'msg' => $msg];
			$result['data'] = $data;
		if (!empty($code))
			$result['code'] = $code;

		$this -> json($result,200);
	}

	/**
	 * 返回操作失败的JSON数据
	 * @access protected
	 * @author
	 *
	 * @param string    $msg    操作成功提示信息
	 * @param array     $data   返回的结果数据
	 * @param string    $id     返回的相关编号
	 * @param int       $code   HTTP Status Code
	 * @return void
	 */
	protected function failure($data = [], $msg = 'error', $code=400) 
	{
		$result = ['success' => false, 'msg' => $msg];
		if (!empty($data))
			$result['data'] = $data;

		$this -> json($result,$code);
	}

	/**
	 * 返回操作失败的JSON数据
	 * @access private
	 *
	 * @param $data 需要格式化的数据
	 * @return void
	 */
	public function json($data,$status_code=200) {
		$result = json_encode($data);
		$this -> response
		-> setStatusCode($status_code, '')
		-> setContent($result) -> send();
		exit;
	}
}