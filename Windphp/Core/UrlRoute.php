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
class UrlRoute {
	
	private static $controller = 'Index';
	private static $action = 'Index';
	
	
	/**
	 * @todo 请求初始化
	 */
	public static function initGet(){
		(!isset($_SERVER['REQUEST_URI']) || (isset($_SERVER['HTTP_X_REWRITE_URL']) && $_SERVER['REQUEST_URI'] != $_SERVER['HTTP_X_REWRITE_URL'])) && self::__requestUriFix();
		!isset($_GET['controller']) && $_GET['controller'] = self::$controller;
		!isset($_GET['action']) && $_GET['action'] = self::$action;
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
			$_GET['controller'] = isset($arr[0]) && preg_match("/^\w+$/", $arr[0]) ?trim($arr[0]) : self::$controller;
			$_GET['action'] = isset($arr[1]) && preg_match("/^\w+$/", $arr[1]) ? trim($arr[1]) : self::$action;
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
