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
	protected $_action = null;
	protected $_controller = null;
	
	
	function __construct($conf) {
		$this->conf = $conf;
		$this->_controller = $this->conf['controller'];
		$this->_action = $this->conf['action'];
	}
	
	
	public function __destruct(){
		$this->debug();
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