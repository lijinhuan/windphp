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

class core {
	public static $conf = array();
	public static $action = 'index';
	public static $do = 'index';
	//核心类加载池
	private static $_coreClasses = array(
			'base_controller' => 'base/controller.base.php',
			'misc' => 'base/misc.class.php',
			'db_model' => 'base/db_model.base.php',
			'db_interface' => 'db/db_interface.php',
			'db_mysqli' => 'db/db_mysqli.class.php',
			'tpl_interface' => 'tpl/tpl_interface.php',
			'tpl_smallTemplate' => 'tpl/tpl_smallTemplate.class.php',
			'cache_interface' => 'cache/cache_interface.php',
			'file_cache' => 'cache/file_cache.class.php',
			'memcache_cache' => 'cache/memcache_cache.class.php',
			'redis_cache' => 'cache/redis_cache.class.php',
			
			'http' => 'library/class.http.php',
			'fileDir' => 'library/class.fileDir.php',
			'showPage' => 'library/class.showPage.php',
	);
	
	
	
	/**
	 * @todo 运行初始化
	 */
	public static function init($conf){
		self::$conf = $conf;
		//时间
		if(isset($conf['timezone']) && !empty($conf['timezone'])){
			date_default_timezone_set($conf['timezone']);
		}else{
			date_default_timezone_set('Asia/Shanghai');
		}
		//调试
		if(DEBUG) {
			error_reporting(E_ALL);
			@ini_set('display_errors', 'ON');	
		} else {
			error_reporting(0);
			@ini_set('display_errors', 'OFF');
		}
		// 最低版本需求判断
		PHP_VERSION < '5.0' && exit('php版本过低，仅支持php5.0版本以上！');
		//全局变量赋值
		$_SERVER['starttime'] = microtime(true);
		$_SERVER['time'] = time();
		$_SERVER['sqls'] = array();
		(!isset($_SERVER['REQUEST_URI']) || (isset($_SERVER['HTTP_X_REWRITE_URL']) && $_SERVER['REQUEST_URI'] != $_SERVER['HTTP_X_REWRITE_URL'])) && self::request_uri_fix();
		self::init_get();
		// 自动 include
		spl_autoload_register(array('core', 'autoload_class'));
		// 异常处理类
		set_exception_handler(array('core', 'exception_handle'));
	}
	

	public static function autoload_class($className){
		// use include so that the error PHP file may appear
	    if(isset(self::$_coreClasses[$className])){
			include_once(FRAMEWORK_PATH.self::$_coreClasses[$className]);
		}else{
			$extlib_file = APP_PATH.'extlib/class.'.$className.'.php';
			if(strpos($className, "_controller")!==false){
				$controller_file = APP_PATH.'controllers/controller.'.str_replace("_controller", "", $className).'.php';
				if(!is_file($controller_file)){
					throw new Exception("$controller_file 文件不存在！");
				}
				include_once($controller_file);
			}else if(strpos($className, "_model")!==false){
				$model_file = APP_PATH.'models/'.$className.'.php';
				if(!is_file($model_file)){
					throw new Exception("$model_file 文件不存在！");
				}
				include_once($model_file);
			}else if(is_file($extlib_file)){
				include_once($extlib_file);
			}else{
				throw new Exception("$className 类不存在！");
			}
			
		}
		return true;
	}
	
	
	
	/**
	 * @todo 应用启动
	 */
	public static function run($conf=array()){
		//初始化
		self::init($conf);
		$control = strtolower($_GET['action']);
		$do = strtolower($_GET['do']);
		$controllerFile = APP_PATH.'controllers/'."controller.{$control}.php";
		if(is_file($controllerFile) && include $controllerFile) {
			$controlclass = "{$control}_controller";
			$onaction = "action_$do";
			$conf['action'] = $do;
			$conf['controller'] = $control;
			$newcontrol = new $controlclass($conf);
			if(method_exists($newcontrol, $onaction)) {
				$newcontrol->$onaction();
			} else {
				throw new Exception("$do 方法不存在！");
			}
		}else{
			throw new Exception("{$control} 控制器 不存在！");
		}
		unset($control,$controllerFile);
	}
	
	
	/**
	 * 修正 IIS  $_SERVER[REQUEST_URI]
	 *
	 */
	private static function request_uri_fix() {
		if(isset($_SERVER['HTTP_X_REWRITE_URL'])) {
			$_SERVER['REQUEST_URI'] = &$_SERVER['HTTP_X_REWRITE_URL'];
		} else if(isset($_SERVER['HTTP_REQUEST_URI'])) {
			$_SERVER['REQUEST_URI'] = &$_SERVER['HTTP_REQUEST_URI'];
		} else {
			if(isset($_SERVER['SCRIPT_NAME'])) {
				$_SERVER['HTTP_REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
			} else {
				$_SERVER['HTTP_REQUEST_URI'] = $_SERVER['PHP_SELF'];
			}
			if(isset($_SERVER['QUERY_STRING'])) {
				$_SERVER['REQUEST_URI'] = '?' . $_SERVER['QUERY_STRING'];
			} else {
				$_SERVER['REQUEST_URI'] = '';
			}
		}
	}
	
	
	/**
	 * get 请求初始化
	 */
	private static function init_get(){
		
		!isset($_GET['action']) && $_GET['action'] = self::$action;
		!isset($_GET['do']) && $_GET['do'] = self::$do;
		$request_uri = $_SERVER['REQUEST_URI'];
		
		preg_match("/\/.*?\?(.*?)\.(html|htm)/is",$request_uri,$match);
		
		if(isset($match[2])){
			$arr = explode('-', $match[1]);
			$num = count($arr);
			if($num > 2) {
				for($i=2; $i<$num; $i+=2) {
					isset($arr[$i+1]) && $_GET[$arr[$i]] = $arr[$i+1];
				}
			}
			$_GET['action'] = isset($arr[0]) && preg_match("/^\w+$/", $arr[0]) ?trim($arr[0]) : self::$action;
			$_GET['do'] = isset($arr[1]) && preg_match("/^\w+$/", $arr[1]) ? trim($arr[1]) : self::$do;
			unset($arr,$num);
		}
		
	}
	

	public static function get_param($k, $var = 'G') {
		switch($var) {
			case 'G': $var = &$_GET; break;
			case 'P': $var = &$_POST; break;
			case 'C': $var = &$_COOKIE; break;
			case 'R': $var = isset($_GET[$k]) ? $_GET : (isset($_POST[$k]) ? $_POST : $_COOKIE); break;
			case 'S': $var = &$_SERVER; break;
		}
		return isset($var[$k]) ? $var[$k] : NULL;
	}
	
	
	public static function ob_clean() {
		!empty($_SERVER['ob_stack']) && count($_SERVER['ob_stack']) > 0 && ob_clean();
	}
	
	
	public static function exception_handle($e,$status=-1) {
		if(self::get_param('ajax') or (isset(self::$conf['restapi'])) and (DEBUG<1 or (DEBUG==1 and !TRACE))){
			exit(json_encode(array('status'=>$status,'msg'=>$e->getMessage())));
		}
		
		if(DEBUG>0){
			echo '<div style="padding:20px;border:2px solid red;background:#e1e1e1;margin:10px;border-radius:10px;">';
			echo "<font color=green><b>Message:</b></font> ".$e->getMessage()."<br/>";
			echo "<font color=green><b>File:</b></font> " .  $e->getFile()."<br/>";
			echo "<font color=green><b>Line: </b></font>" . $e->getLine()."<br/>";
			echo "<font color=green><b>Code: </b></font>" . $e->getCode()."<br/>";
			
			if(DEBUG>1){
				echo "<pre>";
				print_r($e->getTrace());
				echo "</pre>";
			}
			echo "<font color=green><b>PowerBy: </b></font>windphp framework<br/>";
			echo '</div>';
		}else{
			echo $e->getMessage();
		}
		exit;
	}

	
	public static function model($dbtag,$modelname,$conf=array()){
		static  $db_obj_arr = array();
		$file = APP_PATH.'models/'.$dbtag.'_'.$modelname.'_'.'model.php';
		$static_key = md5($file);
		if(isset($db_obj_arr[$static_key])) {
			return $db_obj_arr[$static_key];
		}
		if(is_file($file)){
			include_once $file;
			$class = $dbtag.'_'.$modelname.'_model';
			$model = new $class($conf);
			$model->dbtag = $dbtag;
			$db_obj_arr[$static_key] = $model;
			
			return $model;
		}else{
			$obj = new db_model($conf);
			$obj->dbtag = $dbtag;
			$obj->table = $modelname;
			$db_obj_arr[$static_key] = $obj;
			return $obj;
		}
	}
	
	
	public static function db($dbconf){
		static  $db_obj_arr = array();
		$dbDriver = 'db_'.$dbconf['type'];
		$key = $dbDriver.$dbconf['database'];
		if(isset($db_obj_arr[$key])) {
			return $db_obj_arr[$key];
		}
		$obj = new $dbDriver($dbconf);
		$db_obj_arr[$key] = $obj;
		return $obj;
	}
	
	
	/**
	 * 获取缓存实例
	 * @param string $cache_type file 文件，memcache ，redis等
	 * @param  $conf 系统配置数组
	 * @param string $flag 使用哪一个memcache服务器，在配置文件自定义，如user，不填根据hash来分配
	 * @return obj
	 */
	public static function cache($cache_type,$conf,$flag=''){
		static  $cache_obj_arr = array();
		$static_key = $cache_type.$flag;
		if(isset($cache_obj_arr[$static_key])) {
			return $cache_obj_arr[$static_key];
		}
		$cache_name = $cache_type.'_cache';
		$conf['cache_flag'] = $flag;
		$obj =  new $cache_name($conf);
		$cache_obj_arr[$static_key] = $obj;
		return $obj;
	}
	
	
	public static function getWebUrl($key){
		if(self::$conf['url_rewrite']){
			return self::$conf['app_url'].''.$key.'.html';
		}else{
			return self::$conf['app_url'].'?'.$key.'.html';
		}
	}
	
}


?>