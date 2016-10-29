<?php
/**
 * Copyright (C) windphp framework
 * @todo 用户服务
 */
namespace Services;

use Windphp\Windphp;
use Windphp\Service\IService;
use Windphp\Misc\Utils;
use Windphp\Web\Request;
use Windphp\Core\Config;
class UserService extends IService {
	
	
	/**
	 * 开启session
	 */
	protected function  sessionStart(){
		$session = session_id();
		if( empty($session) ){
			session_start();
		}
	}
	
	
	/**
	 * @todo 检测用户是否登录
	 */
	public  function checkLogin() {
		$this->sessionStart();
		if(!isset($_SESSION['userid']) || !isset($_SESSION['roleid']) ||  !isset($_SESSION['username']) || !$_SESSION['userid'] || !$_SESSION['roleid'] || !$_SESSION['username']){
			return false;
		}
		return true;
	}
	
	
	/**
	 * @todo 获取登录用户信息
	 */
	public function getLoginUser() {
		$user = $this->adminUserDao->fetchOne(array('where'=>array('username'=>$this->getSessionUsername())));
		if(empty($user) or $user['status']==0){
			return $this->error('用户已经被注销');
		}else{
			return $this->success($user);
		}
	}
	
	
	/**
	 * 获取登录用户名
	 */
	protected function getSessionUsername(){
		$this->sessionStart();
		if(isset($_SESSION['username']) && $_SESSION['username']){
			return $_SESSION['username'];
		}else{
			return '';
		}
	}
	
	
	/**
	 * @todo 登录
	 */
	public function  doLogin($username,$password,$checkcode) {
		$this->sessionStart();
		if(strtolower($checkcode)!=strtolower($_SESSION['checkcode'])){
			return $this->error('验证码不正确',-1);
		}
		
		$user_info = $this->adminUserDao->fetchOne(array('where'=>array('username'=>$username)));
		if(empty($user_info) or $user_info['status']==0){
			return $this->error('该用户不存在！',-2);
		}
		if(empty($user_info['roleid'])){
			return $this->error('你无权登录此系统！',-3);
		}
		$password = md5($user_info['salt'].Config::getSystem('autokey').$password);
		
		if($password!=$user_info['password']){
			return $this->error('密码不正确',-4);
		}else{
			$_SESSION['userid'] = $user_info['id'];
			$_SESSION['username'] = $user_info['username'];
			$_SESSION['roleid'] = $user_info['roleid'];
			$url_explode =  explode('.',parse_url(Config::getSystem('app_url'),PHP_URL_HOST));
			$domain = $url_explode[count($url_explode)-2].'.'.$url_explode[count($url_explode)-1];
			setcookie("backend_session_id",session_id(),time()+3600*24*365*10,"/",".{$domain}");
			$data['lastloginip'] = Request::getIp();
			$data['lastlogintime'] = $_SERVER['time'];
			$this->adminUserDao->update(array('set'=>$data,'where'=>array('id'=>$user_info['id'])));
			return $this->success('成功登录');
		}
		
	}
	
	
	
	public function loginOut() {
		$this->sessionStart();
		if(isset($_SESSION['userid']))unset($_SESSION['userid']);
		if(isset($_SESSION['roleid']))unset($_SESSION['roleid']);
		if(isset($_SESSION['username']))unset($_SESSION['username']);
	}
	
	
	
	/**
	 * @todo 合并扩展信息
	 */
	public function mergeExtend($list){
		if(empty($list))return $list;
		foreach ($list as $k=>$v){
			is_string($v['roleid']) and $v['roleid'] = explode(',', $v['roleid']);
			$roles = $this->adminRoleDao->fetchAll(array('where'=>array('id'=>array('in'=>$v['roleid']))));
			$v['rolename'] =  '';
			foreach ($roles as $r){
				$v['rolename'] .= ' '.$r['rolename'];
			}
			$department = $this->departmentDao->fetchOne(array('where'=>array('id'=>$v['department_id'])));
			$v['department_name'] =  isset($department['name'])?$department['name']:$v['department_id'];
			$list[$k] = $v;
		}
		return array_values($list);
	}
		
}
?>
