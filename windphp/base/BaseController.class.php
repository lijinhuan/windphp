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
	protected $_useTpl = false;
	protected $_action = null;
	protected $_controller = null;
	
	
	function __construct($conf) {
		$this->conf = $conf;
		$this->_controller = $this->conf['controller'];
		$this->_action = $this->conf['action'];
	}
	
	
	public function __destruct(){
		if($this->_useTpl){$this->outPut();}
		$this->debug();
	}
	
	
	public function __get($var) {
		if($var=='tpl'){
			$this->_useTpl = true;
		}
		return Core::setMagicGet($var, $this->conf);
	}
	
	
	
	/**
	 * @todo 压缩输出
	 */
	public function outPut(){
		$content = ob_get_clean();
		if (function_exists('ob_gzhandler')) {
			ob_start('ob_gzhandler');
		} else {
			ob_start();
		}
		echo $content;
		ob_end_flush();
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