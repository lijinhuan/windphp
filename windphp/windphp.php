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

//自动转义函数处理
define('MAGIC_QUOTES_GPC', function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc());if(phpversion() < '5.3.0' || MAGIC_QUOTES_GPC){set_magic_quotes_runtime(0);}

//全局函数禁用
if (isset($_GET['GLOBALS']) || isset($_POST['GLOBALS']) || isset($_COOKIE['GLOBALS']) || isset($_FILES['GLOBALS']))exit('globals error! you can not use $_GET[\'GLOBALS\'],$_POST[\'GLOBALS\']...');

// 调试模式: 0:关闭; 2调试模式，线上关闭
defined('DEBUG') or define('DEBUG', 1);

defined('TRACE') or define('TRACE', 0);

//命令行
defined('IS_CLI') or define('IS_CLI', 0);

//框架目录
defined('FRAMEWORK_PATH') or	define('FRAMEWORK_PATH', str_replace('\\', '/', dirname(__FILE__)).'/');

//项目路径
defined('APP_PATH') or define("APP_PATH", dirname($_SERVER['SCRIPT_FILENAME']).'/');//用户项目的应用路径

if(IS_CLI){
	$app_url = "";
}else{
	$http_str =  "http://";
	if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']){
		$http_str =  "https://";
	}
	$portadd = $_SERVER['SERVER_PORT'] == 80 ? '' : ':'.$_SERVER['SERVER_PORT'];
    $path = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
    $app_url =   $http_str."{$_SERVER['HTTP_HOST']}{$portadd}{$path}/";
}
defined('APP_URL') or define("APP_URL", $app_url);

//安装检测
$conf_file =  APP_PATH.'config/conf.inc.php';
if(!is_file($conf_file)){
	$uuid = md5(uniqid().time());
	$create_dirs = array(
			APP_PATH.'config',
			APP_PATH.'controllers',
			APP_PATH.'models',
			APP_PATH.'runtime',
			APP_PATH.'views',
			APP_PATH.'logs',
			APP_PATH.'runtime/data',
			APP_PATH.'runtime/tpl',
			APP_PATH.'views/default',
			APP_PATH.'extlib',
	);
	foreach($create_dirs as $dir){
		if(!is_dir($dir)){
			if(!mkdir($dir,0777)){
				exit($dir.'-创建失败,无权限<br/>');
			}
		}
	}
	$conf_str=<<<conf
<?php
        return array(
			'autokey' => '{$uuid}',//不能删除应用唯一识别id	
			'timezone' => 'Asia/Shanghai',
			'template_syntax' => 'smallTemplate',
			'template_theme' => 'default',
			'data_default_cache_time' => 900,
			'cache_type' => 'file',
			'maxpage' => 500,
			'page_rows' => 20,
			'app_url' => '{$app_url}',
			'support_cache' => array('memcache','redis','file'),
			'db' => array(
        	 		'restaurant' => array(
        	 				'type' => 'mysqli',
        	 				'host'	=> 'localhost:3306',
        	 				'username'	=> 'root',
        	 				'password'	=> '',
        	 				'database'	=> 'test',
        	 				'_charset'	=> 'utf8',
        	 		),
        	 ),
        	 'memd'=> array(
        			'user' => array(
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
        	 		'user' => array(
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
conf;
	file_put_contents($conf_file,$conf_str);
	$index_contrl_str=<<<trol
<?php
/**
 * Copyright (C) windphp framework
 * @todo IndexController
 */   
class IndexController extends BaseController {
		
	public function actionIndex(){
		echo '<h2>windphp framework hello world！</h2>';
	}
		
}
?>
trol;
	file_put_contents(APP_PATH.'controllers/IndexController.class.php',$index_contrl_str);
}

$runtime_file = FRAMEWORK_PATH.'_wind_runtime.php';
if(DEBUG > 0) {
	if(is_file($runtime_file))unlink($runtime_file);
	include FRAMEWORK_PATH.'base/Core.class.php';
}else{
	$content = '';
	$runtime_file = FRAMEWORK_PATH.'_wind_runtime.php';
	if (!is_file($runtime_file)) {
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'base/Core.class.php');
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'base/BaseController.class.php');
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'base/DbModel.class.php');
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'base/Misc.class.php');
		
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'cache/CacheInterface.php');
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'cache/FileCache.class.php');
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'cache/MemcacheCache.class.php');
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'cache/RedisCache.class.php');
	
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'db/DbInterface.php');
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'db/DbMysqli.class.php');
		
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'library/FileDir.class.php');
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'library/Http.class.php');
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'library/ShowPage.class.php');
		
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'tpl/TplInterface.php');
		$content .= php_strip_whitespace(FRAMEWORK_PATH.'tpl/TplSmallTemplate.class.php');
		
		file_put_contents($runtime_file, $content);
		unset($content);
	}
	include $runtime_file;
}
$conf = include $conf_file;

//头输出
if(IS_CLI){
	
}else{
	header('X-Powered-By: WindPHP;');
	if((isset($_SERVER['REMOTE_ADDR']) and !isset($conf['restapi'])) or (DEBUG>1 or (DEBUG and TRACE))){
		header("Expires: 0");
		header("Cache-Control: private, post-check=0, pre-check=0, max-age=0");
		header("Pragma: no-cache");
		header('Content-Type: text/html; charset=UTF-8');
	}else{
		header( "Content-Type: application/json; charset=UTF-8" );
	}
}


