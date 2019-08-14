<?php
/*
|--------------------------------------------------------------------------
| 视图
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/
use Phalcon\Mvc\User\Component;

class WebPages extends Component {
	
	/**
	 * 初始化页面
	 * @author huzhichao502@gmail.com
	 * @param $title string 页面的title信息
	 * @param $nav string 页面的导航
	 * @param $keywords string 页面的关键字
	 * @param $description string 页面的详细内容
	 */
	public function init($title,$nav='',$keywords='',$description='',$layout='layout_main')
	{
		// 设置布局文件
		if (empty($layout_main))
		{
			$this -> view -> setLayout('layout_main');
		}	
		else {
			$this -> view -> setLayout($layout);
		}
		$this->page_url();
		$this->page_keywords($keywords);
		$this->page_description($description);
		$this->page_title($title);
		//$this->page_nav($nav,$section);
		$this->page_member();
		
		$this->page_assets();
	}

	private function page_assets()
	{
		$static = require_once(ROOT_PATH.'/configs/inc_assets.php');		
			
		$header = new Phalcon\Assets\Collection();
		$footer = new Phalcon\Assets\Collection();
		// 全局资源
		$g = $static['global'];
		if ($g) {
			$css = $g['css'];
			foreach ($css as $k => $v) {
				$header -> addCss($v['path']);
			}
			
			$js = $g['js'];
			foreach ($js as $k => $v) {
				$footer -> addJs($v['path']);
			}
		}
		$this -> assets -> set('header', $header);
		$this -> assets -> set('footer', $footer);
	}
	
	/**
	 * 定义页面上URL
	 * @author huzhichao502@gmail.com
	 */
	private function page_url()
	{		
		$this -> assign("version", $this->get_config('js_css_version'));
		$urls = $this->config->url;
		foreach ($urls as $k=>$v)
		{
			$this -> assign("{$k}",$v);
		}
		$this -> assign("dns",$urls);   // dns 刷新
	}
	
	/**
	 * 页面上Header的关键词
	 * @author huzhichao502@gmail.com
	 * @param $keywords string 关键词
	 */
	private function page_keywords($keywords)
	{
		if (empty($keywords))
		{
			$keywords = $this->get_config('keywords');
		}		
		$this-> assign("keywords", $keywords);
	}
	
	/**
	 * 页面上Header的页面描述
	 * @author huzhichao502@gmail.com
	 * @param $description string 页面描述
	 */
	private  function page_description($description)
	{
		if (empty($description))
		{
			$description = $this->get_config('description');
		}	
		$this -> assign("description", $description);
	}
	
	/**
	 * 页面标题
	 * @author huzhichao502@gmail.com
	 * @param $title string 页面标题
	 */
	private  function page_title($title)
	{
		$title = "{$title} | {$this->config->application->suffix}";
		$this -> assign("title", $title);
	}
	
	/**
	 * 页面用户资料
	 * @author huzhichao502@gmail.com
	 * @return void
	 */
	public  function page_member()
	{
		if (isset($this->auth))
		{
			$nickname = $this->auth->getNickname();
			$memberid = $this->auth->getMemberId();
		}
		else {
			$nickname = '';
			$memberid = '';
		}
		$this -> assign("nickname", $nickname);
		$this -> assign("id", $memberid);
	}	
	
	/**
	 * 页面导航，定位到具体的页面
	 * @author huzhichao502@gmail.com
	 * @param $nav string
	 * @return void
	 */
	public  function page_nav($nav='',$section='')
	{
		$this->assign("nav",$nav);
		if (empty($section))
			$section = "I LOVE LIFE";
		$this->assign("section",$section);	
	}
    
	/**
	 * 设置视图变量
	 * @author huzhichao502@gmail.com
	 * @param $name string 变量名
	 * @param $value object 变量值
	 * @return void
	 */
	private function assign($name, $value = '')
	{
		$this-> view -> setVar($name, $value);
	}
	
	/**
	 * 读取配置
	 * @author huzhichao502@gmail.com
	 * @param $name string
	 * @return string
	 */
	private function get_config($name)
	{
		return $this->config->application->$name;
	}
}
