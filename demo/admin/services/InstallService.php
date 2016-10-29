<?php
/**
 * Copyright (C) windphp framework
 * @todo 安装检测
 */
namespace Services;

use Windphp\Windphp;
use Windphp\Misc\Utils;
use Windphp\Web\Response;
use Windphp\Core\UrlRoute;
use Windphp\Service\IService;		
use Windphp\Core\Config;
class InstallService extends IService {
	
	protected $lock_one = '';
	protected $all_lock = '';
	protected $environment_config_file = '';
	
	public function __construct() {
		$this->all_lock =  Windphp::getRuntimePath().'install_lock.txt';
		$this->lock_one = Windphp::getRuntimePath().'install_lock_1.txt';
		$this->environment_config_file = Windphp::getConfigPath().'conf.'.Config::getSystem('environment').'.php';
	}
	
	
	/**
	 * @todo 安装检测服务
	 */
	public function checkInstall(){
		//安装检查
		if(!$this->checkStatus()){
			$js_url = Windphp::$appUrl."script/js/'";
			$image_url = Windphp::$appUrl."script/images/'";
			$css_url = Windphp::$appUrl."script/css/'";
			//替换
			$conf = file_get_contents(Windphp::getConfigFile());
			$autokey = md5(Utils::createRandomstr(32));
			$conf = preg_replace('/\'autokey\'\s*\=\>\s*\'.*?\'/is',"'autokey' => '".$autokey."'",$conf);
			$conf = preg_replace('/\'app_url\'\s*\=\>\s*\'.*?\'/is',"'app_url' => '".Windphp::$appUrl."'",$conf);
			$conf = preg_replace('/\'image_url\'\s*\=\>\s*\'.*?\'/is',"'image_url' => '".$image_url,$conf);
			$conf = preg_replace('/\'js_url\'\s*\=\>\s*\'.*?\'/is',"'js_url' => '".$js_url,$conf);
			$conf = preg_replace('/\'css_url\'\s*\=\>\s*\'.*?\'/is',"'css_url' => '".$css_url,$conf);
			$re = @file_put_contents(Windphp::getConfigFile(),$conf);
			if(!$re){exit(Windphp::getConfigFile().' 无写权限');}
			Response::showMessage('正在进入安装流程...',UrlRoute::getWebUrl('Install-Run-t-'.time()));
		}
	}
	
	
	
	/**
	 * @todo 初始化数据库
	 */
	public function initDatabase($conf) {
		$link = new \Windphp\Db\DbMysqli($conf);
		$link->userTestDb = true;
		$link->showError = false;
		if(!$link->mysqliLink and $link->errorInfo){
			return $this->error($link->errorInfo);
		}
		if(!$link->selectDb){
			$re = $link->query("create database {$conf['database']}");
			if(!$re){
				return $this->error($conf['database'].'数据库创建失败');
			}
		}
		$link->switchDb($conf['database']);
		//创建数据库表
		$sqls = file_get_contents(Windphp::getRuntimeDataPath().'install.sql');
		$sql_arr = explode(';',$sqls);
		foreach($sql_arr as $val){
			$val = trim($val);
			if($val){
				$flag = $link->query($val);
				if(!$flag){
					return $this->error($conf['database'].'数据库'.$val.'表创建失败');
				}
			}
		}
		$install_china_sql = file_get_contents(Windphp::getRuntimeDataPath().'china.sql');
		$sql_arr = explode(';',$install_china_sql);
		foreach($sql_arr as $val){
			$val = trim($val);
			if($val){
				$link->query($val);
			}
		}
		$conf_content = file_get_contents($this->environment_config_file);
		$autokey = md5(Utils::createRandomstr(32));
		$conf_content = preg_replace('/\'host\'\s*\=\>\s*\'.*?\'/is',"'host' => '".$conf['host']."'",$conf_content);
		$conf_content = preg_replace('/\'database\'\s*\=\>\s*\'.*?\'/is',"'database' => '".$conf['database']."'",$conf_content);
		$conf_content = preg_replace('/\'username\'\s*\=\>\s*\'.*?\'/is',"'username' => '".$conf['username']."'",$conf_content);
		$conf_content = preg_replace('/\'password\'\s*\=\>\s*\'.*?\'/is',"'password' => '".$conf['password']."'",$conf_content);
		file_put_contents($this->environment_config_file,$conf_content);
		file_put_contents($this->lock_one,'');
		return $this->success('成功创建数据库');
	}
	
	
	/**
	 * @todo 安装用户初始化
	 */
	public function initUser($info) {
		$salt = Utils::createRandomstr();
		$info['salt'] = $salt;
		$info['password'] =  md5($salt.Config::getSystem('autokey').$info['password']);
		$info['roleid'] = 1;
		$info['department_id'] = 1;
		$insert_id = $this->adminUserDao->insert(array('set'=>$info));
		if($insert_id) {
			@unlink($this->lock_one);
			file_put_contents($this->all_lock,'');
			return $this->success('成功安装');
		}else{
			return $this->error('添加失败');
		}
	}
	
	
	
	
	/**
	 * @todo 检查安装状态，0表示未安装，1表示已经安装，2表示信息尚未完整
	 */
	public function checkStatus() {
		if(file_exists($this->all_lock)){
			return 1;
		}elseif(file_exists($this->lock_one)) {
			return 2;
		}else{
			return 0;
		}
	}
	
	
		
}
?>
