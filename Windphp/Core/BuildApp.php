<?php
/**
 * @todo 应用逻辑基础目录创建类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Core;
use Windphp\Windphp;


class BuildApp {
	
	
	/**
	 * @todo 检测是否已经创建
	 */
	public static function check($rootPath) {
		$conf_file = Windphp::getConfigFile();
		if(is_file($conf_file)){
			return true;
		}
		$create_dirs = array(
				Windphp::getConfigPath(),
				Windphp::getControllersPath(),
				Windphp::getDaosPath(),
				Windphp::getRuntimePath(),
				Windphp::getRuntimeDataPath(),
				Windphp::getRuntimeTplPath(),
				Windphp::getViewsPath(),
				Windphp::getViewsThemePath(),
				Windphp::getLogsPath(),
				Windphp::getComponentsPath(),
				Windphp::getServicesPath()
		);
		foreach($create_dirs as $dir){
			if(!is_dir($dir)){
				if(!@mkdir($dir,0777)){
					exit($dir.'-创建失败,无权限<br/>');
				}
			}
		}
		//=======公共配置=======\\
		self::createCommonConfigFile($conf_file);
		
		//=======测试环境=======\\
		self::createProduceConfigFile();
		
		//=======线上环境=======\\
		self::createOnlineConfigFile();
		
		//创建控制器
		switch (Windphp::$create_mode) {
			case 'web' : 
				self::createWebController();
				break;
			case 'cli' :
				self::createCliController();
				break;
			case 'swoole_http_server':
				self::createSwooleHttpController();
				break;
			case 'swoole_tcp_server':
				self::createSwooleTcpController();
				break;
			case 'swoole_websocket_server':
				self::createSwooleWebSocketController();
				break;
		}	
}	



	/**
	 * @todo 创建公共
	 */
	public static function createCommonConfigFile($conf_file) {
		$uuid = md5(uniqid().time());
		$hostname = gethostname();
		$conf_str=<<<conf
<?php
		
return array(
				'servers_hostname' => array(
	        				'produce' => array('{$hostname}'),
	        				'online' => array(''),
	        	),
				'autokey' => '{$uuid}',//不能删除应用唯一识别id
				'timezone' => 'Asia/Shanghai',
				'template_syntax' => 'smallTemplate',
				'template_theme' => 'default',
				'data_default_cache_time' => 900,
				'cache_type' => 'file',
				'maxpage' => 500,
				'page_rows' => 20,
				'support_cache' => array('memcache','redis','file'),
		
		);
			
?>
conf;
		file_put_contents($conf_file,$conf_str);
	}



	

	/**
	 * @todo 创建正式
	 */
	public static function createOnlineConfigFile() {
		$conf_online_file =   Windphp::getConfigPath().'conf.online.php';
		$online_conf_str=<<<online
<?php
	//正式环境配置
	return array(
		
	);
?>
online;
		file_put_contents($conf_online_file,$online_conf_str);
	}


	/**
	 * @todo 创建测试环境配置文件
	 */
	public static function createProduceConfigFile() {
		$app_url = Windphp::$appUrl;
		$conf_produce_file =  Windphp::getConfigPath().'conf.produce.php';
		$produce_conf_str=<<<produce
<?php
	//测试环境配置
	return array(
			'debug' => '1',
			'trace' => '0',
			'logsql' => '1',
			'log_err' => true,
			'app_url' => '{$app_url}',
			'swoole' => array(
        	 		'host' => '0.0.0.0',
					'http_port' => 9501,
					'websocket_port' => 9502,
					'tcp_port' => 9503,
					'reactor_num' => 2,
				    'worker_num' => 1,
				    'backlog' => 128,
				    'max_request' => 0,
				    'dispatch_mode' => 1,
					'task_worker_num' => 1,
					'daemonize'       => 1,
        	 ),
			'db' => array(
        	 		'default' => array(
        	 				'type' => 'mysqli',
        	 				'host'	=> 'localhost:3306',
        	 				'username'	=> 'root',
        	 				'password'	=> '',
        	 				'database'	=> 'test',
        	 				'_charset'	=> 'utf8',
        	 		),
        	 ),
        	 'memd'=> array(
        			'default' => array(
        					'servers'=>array(
        							'host'=>'127.0.0.1',
        							'port'=>11211,
        							'height'=>75,
        							'auth' => array(
        									//'user' => 'test',
        									//'password'=>'test',
        							),
        					)
        			),
        	 ),
        	 'redis' => array(
        	 		'default' => array(
        	 				'servers'=>array(
        	 						'host'=>'127.0.0.1',
        	 						'port'=>6379,
        	 						'timeout'=>5,
        	 						'auth' => array(
        	 								//'user' => 'test',
        	 								//'password'=>'test',
        	 						),
        	 				)
        	 		),
        	 ),
		
		);
?>
produce;
		file_put_contents($conf_produce_file,$produce_conf_str);
	}

	

	/**
	 * @todo 创建swoole tcp 控制器
	 */
	public static function createSwooleWebSocketController() {
		//=======控制器=======\\
		$swoole_index_contrl_str=<<<trol
<?php
/**
 * Copyright (C) windphp framework
 * @todo SwooleIndexController
 */
namespace Controllers;
use Windphp\Controller\SwooleController;

class SwooleWebSocketIndexController extends SwooleController {

	public function actionIndex(){
		return "windphp framework hello world！\\n";
	}
				
	public function actionOpen() {
		return "connect success welcome to windphp ！\\n";
	}

}
?>
trol;
		file_put_contents(Windphp::getControllersPath().'SwooleWebSocketIndexController.php',$swoole_index_contrl_str);
	}

	



	/**
	 * @todo 创建swoole tcp 控制器
	 */
	public static function createSwooleTcpController() {
		//=======控制器=======\\
		$swoole_index_contrl_str=<<<trol
<?php
/**
 * Copyright (C) windphp framework
 * @todo SwooleIndexController
 */
namespace Controllers;
use Windphp\Controller\SwooleController;

class SwooleTcpIndexController extends SwooleController {

	public function actionIndex(){
		return "windphp framework hello world！\\n";
	}
				
	public function actionOpen() {
		return "connect success welcome to windphp ！\\n";
	}

}
?>
trol;
		file_put_contents(Windphp::getControllersPath().'SwooleTcpIndexController.php',$swoole_index_contrl_str);
	}


	

	/**
	 * @todo 创建swoole http 控制器
	 */
	public static function createSwooleHttpController() {
		//=======控制器=======\\
		$swoole_index_contrl_str=<<<trol
<?php
/**
 * Copyright (C) windphp framework
 * @todo SwooleIndexController
 */
namespace Controllers;
use Windphp\Controller\SwooleController;
		
class SwooleHttpIndexController extends SwooleController {
		
	public function actionIndex(){
		return "windphp framework hello world！\\n";
	}
		
}
?>
trol;
		file_put_contents(Windphp::getControllersPath().'SwooleHttpIndexController.php',$swoole_index_contrl_str);
	}



	/**
	 * @todo 创建cli控制器
	 */
	public static function createCliController() {
		//=======控制器=======\\
		$cliindex_contrl_str=<<<trol
<?php
/**
 * Copyright (C) windphp framework
 * @todo CliIndexController
 */
namespace Controllers;
use Windphp\Controller\CliController;
		
class CliIndexController extends CliController {
		
	public function actionIndex(){
		echo "windphp framework hello world！\\n";
	}
		
}
?>
trol;
		file_put_contents(Windphp::getControllersPath().'CliIndexController.php',$cliindex_contrl_str);
		
	}


	/**
	 * @todo 创建web控制器
	 */
	public static function createWebController() {
		$index_contrl_str=<<<trol
<?php
/**
 * Copyright (C) windphp framework
 * @todo IndexController
 */
namespace Controllers;
use Windphp\Controller\CController;
		
class IndexController extends CController {
		
	public function actionIndex(){
		echo '<h2>windphp framework hello world！</h2>';
	}
		
}
?>
trol;
		file_put_contents(Windphp::getControllersPath().'IndexController.php',$index_contrl_str);
	}
	
	
	
	
	
    
}
