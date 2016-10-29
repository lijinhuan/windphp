<?php
/**
 * @todo web url 路由类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Core;



use Windphp\Misc\Utils;
use Windphp\Windphp;
class UrlRoute {
	
	public static $controller = 'Index';
	public static $action = 'Index';
	public static $controller_name = 'controller';
	public static $action_name = 'action';
	public static $current_controller = '';
	public static $current_action = '';
	
	
	/**
	 * @todo 请求初始化
	 */
	public static function initGet(){
		if(Config::getSystem('default_controller',false))self::$controller = Config::getSystem('default_controller',false);
		if(Config::getSystem('default_action',false))self::$action = Config::getSystem('default_action',false);
		(!isset($_SERVER['REQUEST_URI']) || (isset($_SERVER['HTTP_X_REWRITE_URL']) && $_SERVER['REQUEST_URI'] != $_SERVER['HTTP_X_REWRITE_URL'])) && self::__requestUriFix();
		!isset($_GET[self::$controller_name]) && $_GET[self::$controller_name] = self::$controller;
		!isset($_GET[self::$action_name]) && $_GET[self::$action_name] = self::$action;
		$request_uri = $_SERVER['REQUEST_URI'];
		$rule = "/\/\??(.*?)\.(html|htm)/is";
		preg_match($rule,$request_uri,$match);
		if(isset($match[2])){
			if(Utils::inString('?',$match[1])) {
				$arr_match = explode('?', $match[1]);
				 $match[1] = $arr_match[1];
			}
			$arr = explode('-', $match[1]);	
			$num = count($arr);
			if($num > 2) {
				for($i=2; $i<$num; $i+=2) {
					isset($arr[$i+1]) && $_GET[$arr[$i]] = $arr[$i+1];
				}
			}
			if(isset($arr[0]) && !preg_match("/^\w+$/", $arr[0])){
				throw new \Exception("access error !");
			}
			if(isset($arr[1]) && !preg_match("/^\w+$/", $arr[1])){
				throw new \Exception("access error !");
			}
			$_GET[self::$controller_name] = isset($arr[0]) && preg_match("/^\w+$/", $arr[0]) ?htmlspecialchars(trim($arr[0])) : self::$controller;
			 $_GET[self::$action_name] = isset($arr[1]) && preg_match("/^\w+$/", $arr[1]) ? htmlspecialchars(trim($arr[1])) : self::$action;
			unset($arr,$num);
		}
		self::$current_controller = $_GET[self::$controller_name];
		self::$current_action = $_GET[self::$action_name];
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
	
	
	/**
	 * @todo 伪静态配置
	 * @param  $key
	 * @return string
	 */
	public static function getWebUrl($key){
		if(Config::getSystem('url_rewrite',false)){
			return Windphp::$appUrl.''.$key.'.html';
		}else{
			return Windphp::$appUrl.'?'.$key.'.html';
		}
	}
	
		
    
}
