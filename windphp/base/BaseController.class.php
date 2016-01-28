<?php
/*
 * windphp v1.0
 * https://github.com/lijinhuan
 *
 * Copyright 2015 (c) 543161409@qq.com
 * GNU LESSER GENERAL PUBLIC LICENSE Version 3
 * http://www.gnu.org/licenses/lgpl.html
 *
 */

if(!defined('FRAMEWORK_PATH')) {
	exit('access error !');
}
  
class BaseController  {
	protected $_page = 1;
	protected $_refer = '';//来源url
	protected $_action = null;
	protected $_controller = null;
	
	
	function __construct($conf) {
		$this->conf = $conf;
		$this->_controller = $this->conf['controller'];
		$this->_action = $this->conf['action'];
		$this->__initSysParam();
	}
	
	
	public function __destruct(){
		$this->debug();
	}
	
	
	/**
	 * @todo 系统参数初始化
	 */
	protected function __initSysParam(){
		$page = abs(Misc::getParam('page'));
		$this->_page = min($page,$this->conf['maxpage']);
		if($page<1){$this->_page = 1;}
		$this->conf['limit_start'] = ($this->_page-1)*$this->conf['page_rows'];
		$this->conf['limit'] = $this->conf['limit_start'].','.$this->conf['page_rows'];
		$this->_refer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:$this->conf['app_url'];
		if(strpos($this->_refer, $this->conf['app_url'])===false)$this->_refer=$this->conf['app_url'];
	}
	
	
	public function __get($var) {
		return Core::setMagicGet($var, $this->conf);
	}
	
	
	/**
	 * @todo 调试输出
	 */
	public function debug(){
		if(DEBUG>1 or TRACE>0){
			echo "<div style='border:2px solid green;padding:20px;border-radius:10px;margin:10px;'>";
			echo 'time: '.(microtime(true)-$_SERVER['starttime']).' &nbsp; &nbsp; &nbsp; memoery:'.(round(memory_get_usage()/1024,2)) .'kb';
			echo "<br/><pre>";
			print_r(get_included_files());
			echo "</pre>";
			echo "<br/><pre>";
			print_r($_SERVER['sqls']);
			echo "</pre>";
			if(DEBUG>1){
				echo "<br/><pre>";
				print_r($this->conf);
				echo "</pre>";
				echo "<br/><pre>";
				print_r($_SERVER);
				echo "</pre>";
			}
			echo "</div>";
		}
	}
	
}
?>