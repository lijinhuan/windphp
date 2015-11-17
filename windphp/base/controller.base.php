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
  
class base_controller  {
	protected $use_tpl = false;	
	
	function __construct($conf) {
		$this->conf = $conf;
	}
	
	
	public function __get($var) {
		static $controlAutoGet = array();
		if(isset($controlAutoGet[$var])){
			return $controlAutoGet[$var];
		}
		if($var=='tpl'){
			$this->use_tpl = true;
			$tplName = 'tpl_'.$this->conf['template_syntax'];
			$driverObj =  new $tplName($this->conf);
			$controlAutoGet[$var] = $driverObj;
			unset($controlAutoGet,$tplName);
			return $driverObj;
		}elseif($var=='file'){
			return core::cache($var,$this->conf);
		}elseif(substr($var,-3,3)=='_db'){
			$var_arr = explode("_", $var);
			$dbconf = $this->conf['db'][$var_arr[0]];
			$driverObj = core::db($dbconf);
			$controlAutoGet[$var] = $driverObj;
			unset($controlAutoGet,$var_arr,$var,$dbconf);
			return $driverObj;
		}else{
			$var_arr = explode("_", $var);
			$count = count($var_arr);
			if($count<2){
				throw new Exception("$var error ！");
			}
			
			if(in_array($var_arr[0], $this->conf['support_cache'])){
				return core::cache($var_arr[0],$this->conf,$var_arr[1]);
			}
			$table = substr($var,strlen($var_arr[0].'_'));
			$driverObj = core::model($var_arr[0],$table,$this->conf);
			$controlAutoGet[$var] = $driverObj;
			unset($controlAutoGet,$count,$var_arr,$var);
			return $driverObj;
		}
	}
	
	
	public function __destruct(){
		if($this->use_tpl){
			$this->out_put();
		}
		$this->debug();
	}
	
	
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
	
	
	public function out_put(){
		$content = ob_get_clean();
		if (function_exists('ob_gzhandler')) {
			ob_start('ob_gzhandler');
		} else {
			ob_start();
		}
		echo $content;
		ob_end_flush();
	}
	
}
?>