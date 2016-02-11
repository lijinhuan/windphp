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

class Core {
	public static $conf = array();
	public static $controller = 'Index';
	public static $action = 'Index';
	//核心类加载池
	private static $__coreClasses = array(
			'BaseController' => 'base/BaseController.class.php',
			'DbModel' => 'base/DbModel.class.php',
			'Extlib' => 'base/Extlib.class.php',
			'Misc' => 'base/Misc.class.php',
			'CacheInterface' => 'cache/CacheInterface.php',
			'FileCache' => 'cache/FileCache.class.php',
			'MemcacheCache' => 'cache/MemcacheCache.class.php',
			'RedisCache' => 'cache/RedisCache.class.php',
			'DbInterface' => 'db/DbInterface.php',
			'DbMysqli' => 'db/DbMysqli.class.php',
			'TplInterface' => 'tpl/TplInterface.php',
			'TplSmallTemplate' => 'tpl/TplSmallTemplate.class.php',
			'FileDir' => 'library/FileDir.class.php',
			'Logger' => 'library/Logger.class.php',
			'Http' => 'library/Http.class.php',
			'ShowPage' => 'library/ShowPage.class.php',
	);
	
	
	/**
	 * @todo 运行初始化
	 */
	public static function init($conf){
		self::$conf = $conf;
		if(isset(self::$conf['default_controller']))self::$controller=self::$conf['default_controller'];
		if(isset(self::$conf['default_action']))self::$action=self::$conf['default_action'];
		
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
		(!isset($_SERVER['REQUEST_URI']) || (isset($_SERVER['HTTP_X_REWRITE_URL']) && $_SERVER['REQUEST_URI'] != $_SERVER['HTTP_X_REWRITE_URL'])) && self::__requestUriFix();
		// 自动 include
		spl_autoload_register(array('Core', 'autoLoadClass'));
		// 异常处理类
		set_exception_handler(array('Core', 'exceptionHandle'));
		self::__initGet();
	}
	
	
	/**
	 * 设置通用魔术方法函数
	 */
	 public static function setMagicGet($var, $conf) {
	 	static $control_auto_get = array();
	 	if(isset($control_auto_get[$var])) {
	 		return $control_auto_get[$var];
		} 

		switch ($var) {
			case 'tpl':
				$tpl_name = 'Tpl'.ucfirst($conf['template_syntax']);
				$driver_obj =  new $tpl_name($conf);
				$control_auto_get[$var] = $driver_obj;
				return $driver_obj;

			case 'file':
				return Core::cache($var,$conf);

			default:
				@list($type, $flag) = explode('_', $var, 2);
				if(empty($type) or empty($flag)) {
					throw new Exception("$var error ！");
				}
				
				if ($flag=='db') {
					$dbconf = $conf['db'][$type];
					$driver_obj = Core::db($dbconf);
					$control_auto_get[$var] = $driver_obj;
					return $driver_obj;
				} elseif (in_array($type, $conf['support_cache'])) {
					return Core::cache($type,$conf,$flag);
				} else {
					$driver_obj = Core::model($type,$flag,$conf);
					$control_auto_get[$var] = $driver_obj;
					return $driver_obj;
				}
		}
	}

	
	public static function autoLoadClass($className) {
		// use include so that the error PHP file may appear
		if(isset(self::$__coreClasses[$className])){
			include_once(FRAMEWORK_PATH.self::$__coreClasses[$className]);
		}else{
			$extlib_file = APP_PATH.'extlib/'.$className.'.class.php';
			if(strpos($className, "Controller")!==false){
				$controller_file = APP_PATH.'controllers/'.str_replace("Controller", "", $className).'Controller.class.php';
				if(!is_string($controller_file) or  !is_file($controller_file)){
					throw new Exception("$controller_file 文件不存在！");
				}
				include_once($controller_file);
			}else if(strpos($className, "Model")!==false){
				$model_file = APP_PATH.'models/'.$className.'.class.php';
				if(!is_string($model_file) or !is_file($model_file)){
					throw new Exception("$model_file 文件不存在！");
				}
				include_once($model_file);
			}else if(is_string($extlib_file) && is_file($extlib_file)){
				include_once($extlib_file);
			}else{
				throw new Exception("$className 类不存在！");
			}
		}
		return true;
	}
	
	
	
	
	
	/**
	 * @todo 错误处理
	 */
	public static function exceptionHandle($e,$status=-1) {
		if(isset($_GET['ajax']) or (isset(self::$conf['restapi'])) and (DEBUG<1 or (DEBUG==1 and !TRACE))){
			exit(json_encode(array('status'=>$status,'msg'=>$e->getMessage())));
		}
		if(DEBUG>0){
			echo "<html><head><title>错误提示</title></head><body>";
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
			echo '</div></body></html>';
		}else{
			if(isset(Core::$conf['web']) and Core::$conf['web']){
				Misc::showMessage($e->getMessage(),Core::$conf['app_url']);
			}else{
				echo $e->getMessage();
			}
		}
		
		exit();
	}
	
	
	/**
	 * @todo 应用启动
	 * @param $conf array 配置
	 */
	public static function run($conf=array()){
		//初始化
		self::init($conf);
		$control = ucfirst(htmlspecialchars($_GET['action']));
		$do = ucfirst(htmlspecialchars($_GET['do']));
		
		$controller_file = APP_PATH.'controllers/'.$control."Controller.class.php";
		if(is_string($controller_file) && is_file($controller_file) && include $controller_file) {
			$control_class = $control."Controller";
			$onaction = "action".$do;
			$conf['action'] = $do;
			$conf['controller'] = $control;
			$newcontrol = new $control_class($conf);
			if(method_exists($newcontrol, $onaction)) {
				$newcontrol->$onaction();
			} else {
				throw new Exception("$do 方法不存在！");
			}
		}else{
			throw new Exception("{$control} 控制器 不存在！");
		}
		unset($control,$controller_file);
	}
	
	
	
	/**
	 * @todo 实例化模型
	 */
	public static function model($dbTag,$modelName,$conf=array()){
		
		$dbTag = ucfirst($dbTag);
		$table_name = $modelName;
		$modelName = ucfirst($modelName);
		static  $db_obj_arr = array();
		$file = APP_PATH.'models/'.$dbTag.$modelName.'Model'.'.class.php';
		
		$static_key = md5($file);
		if(isset($db_obj_arr[$static_key])) {
			return $db_obj_arr[$static_key];
		}
		if(is_string($file) && is_file($file)){
			include_once $file;
			$class = $dbTag.$modelName.'Model';
			
			$model = new $class($conf);
			if(!$model->dbTag){$model->dbTag = $dbTag;}
			if(!$model->table){$model->table = $table_name;}
			$db_obj_arr[$static_key] = $model;
			
			return $model;
		}else{
			$obj = new DbModel($conf);
			$obj->dbTag = $dbTag;
			$obj->table = $table_name;
			$db_obj_arr[$static_key] = $obj;
			return $obj;
		}
	}
	
	
	public static function db($dbconf){
		static  $db_obj_arr = array();
		$db_driver = 'Db'.ucfirst($dbconf['type']);
		$key = $db_driver.$dbconf['database'];
		if(isset($db_obj_arr[$key])) {
			return $db_obj_arr[$key];
		}
		$obj = new $db_driver($dbconf);
		$db_obj_arr[$key] = $obj;
		return $obj;
	}
	
	
	/**
	 * 获取缓存实例
	 * @param string $cacheType file 文件，memcache ，redis等
	 * @param  $conf 系统配置数组
	 * @param string $flag 使用哪一个memcache服务器，在配置文件自定义，如user，不填根据hash来分配
	 * @return obj
	 */
	public static function cache($cacheType,$conf,$flag=''){
		$cacheType = ucfirst($cacheType);
		static  $cache_obj_arr = array();
		$static_key = $cacheType.$flag;
		if(isset($cache_obj_arr[$static_key])) {
			return $cache_obj_arr[$static_key];
		}
		$cache_name = $cacheType.'Cache';
		$conf['cache_flag'] = $flag;
		$obj =  new $cache_name($conf);
		$cache_obj_arr[$static_key] = $obj;
		return $obj;
	}
	
	
	
	public static function getWebUrl($key){
		if(isset(self::$conf['url_rewrite']) and self::$conf['url_rewrite']){
			return self::$conf['app_url'].''.$key.'.html';
		}else{
			return self::$conf['app_url'].'?'.$key.'.html';
		}
	}
	
	
	
	/**
	 * @todo 请求初始化
	 */
	private static function __initGet(){
		!isset($_GET['action']) && $_GET['action'] = self::$controller;
		!isset($_GET['do']) && $_GET['do'] = self::$action;
		$request_uri = $_SERVER['REQUEST_URI'];
		$rule = "/\/\??(.*?)\.(html|htm)/is";
		preg_match($rule,$request_uri,$match);
		
		if(isset($match[2])){
			$arr = explode('-', $match[1]);
			
			$num = count($arr);
			if($num > 2) {
				for($i=2; $i<$num; $i+=2) {
					isset($arr[$i+1]) && $_GET[$arr[$i]] = $arr[$i+1];
				}
			}
			if(isset($arr[0]) && !preg_match("/^\w+$/", $arr[0])){
				Misc::showMessage("该地址不存在",Core::$conf['app_url']);
			}
			if(isset($arr[1]) && !preg_match("/^\w+$/", $arr[1])){
				Misc::showMessage("该地址不存在",Core::$conf['app_url']);
			}
			
			$_GET['action'] = isset($arr[0]) && preg_match("/^\w+$/", $arr[0]) ?trim($arr[0]) : self::$controller;
			$_GET['do'] = isset($arr[1]) && preg_match("/^\w+$/", $arr[1]) ? trim($arr[1]) : self::$action;
			unset($arr,$num);
		}
	}
	
	
	/**
	 * @todo 修正 IIS  $_SERVER[REQUEST_URI]
	 */
	private static function __requestUriFix() {
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
	
}


?>
