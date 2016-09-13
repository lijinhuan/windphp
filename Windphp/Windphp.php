<?php
/**
 * @todo windphp 核心文件
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp;
use Windphp\Core\BuildApp;
use Windphp\Core\Config;
use Windphp\Core\UrlRoute;
use Windphp\Web\Request;
use Windphp\Dao\IDao;


class Windphp {
	
	
	private static $frameWorkClass = array(
				'BuildApp' =>	'Windphp/Core/BuildApp.php',
				'Logger' =>	'Windphp/Core/Logger.php',
				'Config' =>	'Windphp/Core/Config.php',
				'Debug' =>	'Windphp/Core/Debug.php',
				'UrlRoute' =>	'Windphp/Core/UrlRoute.php',
				'CException' =>	'Windphp/Core/CException.php',
				'IController' =>	'Windphp/Controller/IController.php',
				'CController' =>	'Windphp/Controller/CController.php',
				'CliController' =>	'Windphp/Controller/CliController.php',
				'SwooleController' =>	'Windphp/Controller/SwooleController.php',
				'IService' =>	'Windphp/Service/IService.php',
				'IDao' =>	'Windphp/Dao/IDao.php', 
				'DbFactory' =>	'Windphp/Db/DbFactory.php',
				'DbMysqli' =>	'Windphp/Db/DbMysqli.php',
				'DbInterface' => 'Windphp/Db/DbInterface.php',
				'Http' =>	'Windphp/Swoole/Http.php',
				'Tcp' =>	'Windphp/Swoole/Tcp.php',
				'WebSocket' =>	'Windphp/Swoole/WebSocket.php',
				'Request' =>	'Windphp/Web/Request.php',
				'Response' =>	'Windphp/Web/Response.php',
				'Utils' =>	'Windphp/Misc/Utils.php',
				'FileDir' =>	'Windphp/Misc/FileDir.php',
				'HttpClient' =>	'Windphp/Misc/HttpClient.php',
				'ShowPage' =>	'Windphp/Misc/ShowPage.php',
				'TplInterface' => 'Windphp/Template/TplInterface.php',
			    'TplSmallTemplate' => 'Windphp/Template/TplSmallTemplate.php',
				'CacheInterface' =>	'Windphp/Cache/CacheInterface.php',
				'FileCache' =>	'Windphp/Cache/FileCache.php',
				'MemcacheCache' =>	'Windphp/Cache/MemcacheCache.php',
				'RedisCache' =>	'Windphp/Cache/RedisCache.php',
				'IComponent' => 'Windphp/Component/IComponent.php',
	);
	private static $importObjs = array();
	private static $classPath = array();
	private static $windphpFrameworkPath;
	private static $rootPath;
	private static $appDirs = array(
			'confing_dir'=>'confing',
			'controllers_dir'=>'controllers',
			'daos_dir'=>'daos',
			'runtime_dir'=>'runtime',
			'runtime_data_dir'=>'data',
			'runtime_tpl_dir'=>'tpl',
			'views_dir'=>'views',
			'views_theme_dir'=>'default',
			'logs_dir'=>'logs',
			'components_dir'=>'components',
			'services_dir' => 'services',
	);
	
	public static $appUrl = '';
	public static $argv = array();
	public static $create_mode = 'web';
	
	
	public static function getConfigPath() {
		return self::$rootPath.self::$appDirs['confing_dir'].DS;
	}
	
	public static function getControllersPath() {
		return self::$rootPath.self::$appDirs['controllers_dir'].DS;
	}
	
	public static function getDaosPath() {
		return self::$rootPath.self::$appDirs['daos_dir'].DS;
	}
	
	public static function getRuntimePath() {
		return self::$rootPath.self::$appDirs['runtime_dir'].DS;
	}
	
	public static function getRuntimeDataPath() {
		return self::getRuntimePath().self::$appDirs['runtime_data_dir'].DS;
	}
	
	public static function getRuntimeTplPath() {
		return self::getRuntimePath().self::$appDirs['runtime_tpl_dir'].DS;
	}
	
	public static function getViewsPath() {
		return self::$rootPath.self::$appDirs['views_dir'].DS;
	}
	
	public static function getViewsThemePath() {
		return self::getViewsPath().self::$appDirs['views_theme_dir'].DS;
	}
	
	public static function getLogsPath() {
		return self::getRuntimePath().self::$appDirs['logs_dir'].DS;
	}
	
	public static function getComponentsPath() {
		return self::$rootPath.self::$appDirs['components_dir'].DS;
	}
	
	
	public static function getServicesPath() {
		return self::$rootPath.self::$appDirs['services_dir'].DS;
	}
	
	public static function getConfigFile() {
		return self::getConfigPath().'conf.inc.php';
	}
	

	
	/**
	 * @todo 初始化相关信息
	 */
	private static function  init($rootPath) {
		PHP_VERSION < '5.3' && exit('php version error ,  must > 5.3.0 ！');
		$_SERVER['time'] = time();
		defined('DS') or define('DS', DIRECTORY_SEPARATOR);
		self::$windphpFrameworkPath = dirname(__DIR__).DS;
		self::$rootPath = $rootPath;
		\spl_autoload_register(__CLASS__ . '::autoLoadClass');
		// 异常处理类
		\set_exception_handler('Windphp\Core\CException' . '::exceptionHandle');
		BuildApp::check($rootPath);
		//加载config
		Config::loadSystem(self::getConfigFile());
		if(Config::getSystem('debug')) {\Windphp\Core\Debug::systemStartTime();}
		//时间
		\date_default_timezone_set(Config::getSystem('timezone'));
		if(Config::getSystem('debug')) {
			\error_reporting(E_ALL);
			@\ini_set('display_errors', 'ON');
		}else{
			\error_reporting(0);
			@\ini_set('display_errors', 'OFF');
		}
	}
	
	
	/**
	 * @todo 创建web应用
	 * @param string $root_path 业务逻辑所在的根目录
	 */
	public  static function	createWebApplication($rootPath) {
		header('X-Powered-By: Windphp;');
		header("Expires: 0");
		header("Cache-Control: private, post-check=0, pre-check=0, max-age=0");
		header("Pragma: no-cache");
		header('Content-Type: text/html; charset=UTF-8');
		if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']){
			$http_str =  "https://";
			$portadd = $_SERVER['SERVER_PORT'] == 443 ? '' : ':'.$_SERVER['SERVER_PORT'];
		}else{
			$http_str =  "http://";
			$portadd = $_SERVER['SERVER_PORT'] == 80 ? '' : ':'.$_SERVER['SERVER_PORT'];
		}
		$path = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
		self::$appUrl =   $http_str."{$_SERVER['HTTP_HOST']}{$portadd}{$path}/";	
		self::init($rootPath);
		UrlRoute::initGet();
		self::runController($_GET['controller'], $_GET['action']);
	}
	
	
	/**
	 * @todo 创建命令行程序
	 * @param string $root_path 业务逻辑所在的根目录
	 */
	public static function createCliApplication($rootPath,$argv) {
		self::$create_mode = 'cli';
		self::$argv = $argv;
		self::init($rootPath);
		if(!Request::isCli()) {
			exit("access error !\n");
		}
		if(empty($controller)) {
			if(!isset(self::$argv[1])) {
				$controller = 	'CliIndex';
			}else{
				$controller = 	self::$argv[1];
			}
		}
		if(empty($action)) {
			if(!isset(self::$argv[2])) {
				$action = 	'Index';
			}else { 
				$action = 	self::$argv[2];
			}  
		}
		
		self::runController($controller,$action);
	}
	
	
	/**
	 * @todo 创建swoole程序
	 * @param string $root_path 业务逻辑所在的根目录
	 */
	public static function createSwooleApplication($rootPath,$argv) {
		self::$argv = $argv;
		$server  = 	isset(self::$argv[1])?self::$argv[1]:'';
		self::$create_mode = 'swoole_'.strtolower($server).'_server';
		self::init($rootPath);
		if(!Request::isCli()) {
			exit("access error !\n");
		}
		$server = ucfirst($server);
		if(!in_array($server, array('Http','WebSocket','Tcp'))) {
			exit("server type error !\n");
		}
		$application = "create{$server}Application";
		self::$application();
	}
	
	
	/**
	 * @todo 创建swoole http程序
	 * @param string $root_path 业务逻辑所在的根目录
	 */
	public static function createHttpApplication() {
		$swoole_config = Config::getSystem('swoole');
		$set = array('daemonize' => $swoole_config['daemonize']);
		\Windphp\Swoole\Http::Run($swoole_config['host'], $swoole_config['http_port'],$set);
	}
	
	
	/**
	 * @todo 创建swoole http程序
	 * @param string $root_path 业务逻辑所在的根目录
	 */
	public static function createTcpApplication() {
		$swoole_config = Config::getSystem('swoole');
		$set = array('daemonize' => $swoole_config['daemonize']);
		\Windphp\Swoole\Tcp::Run($swoole_config['host'], $swoole_config['tcp_port'],$set);
	}
	
	
	/**
	 * @todo 创建swoole http程序
	 * @param string $root_path 业务逻辑所在的根目录
	 */
	public static function createWebSocketApplication() {
		self::$create_mode = 'swoole_websocket_server';
		$swoole_config = Config::getSystem('swoole');
		$set = array('daemonize' => $swoole_config['daemonize']);
		\Windphp\Swoole\WebSocket::Run($swoole_config['host'], $swoole_config['websocket_port'],$set);
	}
	
	
	/**
	 * @todo 执行控制器
	 */
	public static function runController($controller,$action = '',$run=true,$throw_exception=true) {
		$controller = ucfirst(htmlspecialchars($controller));
		$controller_file = self::getControllersPath().$controller."Controller.php";
		if(is_file($controller_file)) {
			$controller_class = '\Controllers\\'.$controller."Controller";
			$controler_obj = self::import($controller_class,$controller_file,true);	
			if(!$run)return $controler_obj;
			$action = ucfirst(htmlspecialchars($action));
			$on_action = "action".$action;
			if(method_exists($controler_obj, $on_action)) {
				return $controler_obj->$on_action();
			} else {
				$msg  =  "$action 方法不存在！";
				if(!$throw_exception)return $msg;
				throw new \Exception($msg);
			}
		}else{
			$msg = "{$controller} 控制器 不存在！";
			if(Config::getSystem('debug')) {
				$msg .= " - {$controller_file} " ;
			}
			if(!$throw_exception)return $msg;
			throw new \Exception($msg);
		}
	}
	
	
	
	/**
	 * @todo 获取服务
	 */
	public static function getService($service) {
		$service = ucfirst($service);
		$service_file = self::getServicesPath().$service.'Service.php';
		if(is_file($service_file)) {
			$service_class = '\Services\\'.$service."Service";
			return self::import($service_class,$service_file,true);
		}else {
			$msg = "{$service} 服务 不存在";
			if(Config::getSystem('debug')) {
				$msg .= " - {$service_file} " ;
			}
			throw new \Exception($msg);
		}
	}
	
	
	/**
	 * @todo 获取dao
	 */
	public static function getDao($dao) {
		$dao = ucfirst($dao);
		$dao_file = self::getDaosPath().$dao.'Dao.php';
		if(is_file($dao_file)) {
			$dao_class = '\Daos\\'.$dao."Dao";
			return self::import($dao_class,$dao_file,true);
		}else {
			$msg = "{$dao} dao 不存在";
			if(Config::getSystem('debug')) {
				$msg .= " - {$dao_file} " ;
			}
			throw new \Exception($msg);
		}
	}
	
	
	/**
	 * 不需要定义文件去获取dao
	 * @param string $table
	 * @param string $databases
	 */
	public static  function getIDao($table,$databases) {
		static $daos = array();
		$key = $table.$databases;
		if(!isset($daos[$key])){
			$daos[$key] = new IDao();
		}
		$dao = $daos[$key];
		$dao->database = $databases;
		$dao->table = $table;
		return $dao;
	}
	
	
	/**
	 * @todo 获取缓存
	 */
	public static function getCache($type='',$machine='') {
		//选择默认的
		if(empty($type)) {
			$type = Config::getSystem('cache_type');
		}
		$obj_name =  "Windphp\Cache\\".ucfirst($type).'Cache';
		static $caches = array();
		$key = md5($obj_name.$machine);
		if(!isset($caches[$key])) {
			$caches[$key] = new $obj_name($machine);
		}
		return $caches[$key];
	}
	
	
	/**
	 * @todo 获取视图
	 */
	public static function getView() {
		static $views = array();
		$tpl_name = 'Windphp\Template\Tpl'.ucfirst(Config::getSystem('template_syntax'));
		if(!isset($views[$tpl_name])) {
			$views[$tpl_name] =  new $tpl_name();
		}
		return $views[$tpl_name];
	}
	
	
	/**
	 * @todo 加载类
	 * @param string $class 类名
	 * @param string $path 文件路径 
	 * @param boolen $new_obj  是否创建对象
	 * @return obj or boolen
	 */
	public static function import($class,$path,$new_obj=false) {
		if(!isset(self::$classPath[$class])) {
			include  $path;
			self::$classPath[$class] = $path;
		}
		$key = md5($path.$new_obj);
		if(isset(self::$importObjs[$key])) {
			return self::$importObjs[$key];
		}else{
			if($new_obj){
				return self::$importObjs[$key] = new $class();
			}else{
				return self::$importObjs[$key] = true;
			}
		}
	}
	
	
	
	
	/**
	 * @todo 自动加载类
	 * @param string $class 类名称
	 */
	final public static function autoLoadClass($class) {
		$baseClasspath = \str_replace('\\', DS, $class) . '.php';
		if(isset(self::$classPath[$class])) {
			return;
		}
		if(in_array(\str_replace('\\', '/', $class) . '.php', self::$frameWorkClass)) {
			$class_path = self::$windphpFrameworkPath.$baseClasspath;
			self::$classPath[$class] = $class_path;	
            require $class_path;
            return;
		}else{
			$class_path = self::$rootPath.$baseClasspath;
			if(is_file($class_path)) {
				self::$classPath[$class] = $class_path;
				require $class_path;
				return;
			}
		}
	}
	

}











?>