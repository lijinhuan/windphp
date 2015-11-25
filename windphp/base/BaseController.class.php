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
	
	
	function __construct($conf) {
		$this->conf = $conf;
	}
	
	
	public function __destruct(){
		if($this->_useTpl){$this->outPut();}
		$this->debug();
	}
	
	
	public function __get($var) {
		static $control_auto_get = array();
		if(isset($control_auto_get[$var])){
			return $control_auto_get[$var];
		}
		if($var=='tpl'){
			$this->useTpl = true;
			$tpl_name = 'Tpl'.ucfirst($this->conf['template_syntax']);
			$driver_obj =  new $tpl_name($this->conf);
			$control_auto_get[$var] = $driver_obj;
			unset($control_auto_get,$tpl_name);
			return $driver_obj;
		}elseif(substr($var,-3,3)=='_db'){
			$var_arr = explode("_", $var);
			$dbconf = $this->conf['db'][$var_arr[0]];
			$driver_obj = Core::db($dbconf);
			$control_auto_get[$var] = $driver_obj;
			unset($control_auto_get,$var_arr,$var,$dbconf);
			return $driver_obj;
		}else{
			if($var=='file'){
				return Core::cache($var,$this->conf);
			}
			$var_arr = explode("_", $var);
			$count = count($var_arr);
			if($count<2){
				throw new Exception("$var error ！");
			}	
			if(in_array($var_arr[0], $this->conf['support_cache'])){
				return Core::cache($var_arr[0],$this->conf,$var_arr[1]);
			}
			$table = substr($var,strlen($var_arr[0].'_'));
			$driver_obj = Core::model($var_arr[0],$table,$this->conf);
			$control_auto_get[$var] = $driver_obj;
			unset($control_auto_get,$count,$var_arr,$var);
			return $driver_obj;
		}
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