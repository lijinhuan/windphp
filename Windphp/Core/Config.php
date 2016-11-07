<?php
/**
 * @todo 配置类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Core;
use Windphp\Windphp;


class Config {
	
	private static $config_files = array();
	public static $systemConfig = array();
	

	public static function loadSystem($configPath) {
		self::$systemConfig = require $configPath;
		$check_config = false;
		if(isset(self::$systemConfig['environment']) and self::$systemConfig['environment']){
			$conf_file = Windphp::getConfigPath().'conf.'.self::$systemConfig['environment'].'.php';
			if(!is_file($conf_file)){
				throw new \Exception($conf_file." does not exists! ");
			}
			self::$systemConfig += require $conf_file;
			$check_config = true;
		}else{
			foreach (self::$systemConfig['servers_hostname'] as $key=>$val){
				if(in_array(gethostname(),$val)){
					self::$systemConfig['environment'] = $key;
					$conf_file = Windphp::getConfigPath().'conf.'.self::$systemConfig['environment'].'.php';
					if(!is_file($conf_file)){
						throw new \Exception($conf_file." does not exists! ");
					}
					self::$systemConfig += require $conf_file;
					$check_config = true;
					break;
				}
			}
		}
		if(!$check_config){
			
			exit("environment servers_hostname in conf.inc.php not found ! <br/> please use like 'servers_hostname' => array(
	        				'produce' => array('".gethostname()."'),
	        				'online' => array(''),
	        	) <br/> and your hostname is <font color=red>".gethostname()."</font>");
		}
		return self::$systemConfig;
	}
	

	public static function getSystem($key,$throw=true) {
		if(isset(self::$systemConfig[$key]))return self::$systemConfig[$key];
		if($throw){
			throw new \Exception("{$key} key config empty");
		}else {
			return '';
		}
	}
	
	
	public static function setSystem($key,$val) {
		self::$systemConfig[$key] = $val;
	}
	
	
	public static function getConfig($key,$file) {
		if(!isset(self::$config_files[$file])){
			$conf_file = Windphp::getConfigPath().'conf.'.$file.'.php';
			if(!is_file($conf_file)) {
				throw new \Exception($conf_file." does not exists! ");
			}
			$config = require $conf_file;
			self::$config_files[$file] = $config;
		}
		if(isset(self::$config_files[$file][$key]))return self::$config_files[$file][$key];
		throw new \Exception("{$key} key config empty");
	}
	
		
    
}
