<?php
/**
 * Copyright (C) windphp framework
 * @todo 权限服务
 */
namespace Services;


use Windphp\Service\IService;
class RolePrivService extends IService {
	
	/**
	 * @todo 权限初始化
	 */
	public function initCheckRole($user) {
		if(empty($user['roleid'])){
			return $this->error('你无权登录此系统！');
		}
		$role = $this->adminRoleDao->getRole($user['roleid']);
		if(empty($role)){
			return $this->error('你无权登录此系统！');
		}
		$rolename = array();
		$roleid = array();
		foreach ($role as $r){
			if($r['disabled']){
				continue;
			}else{
				$rolename[] = $r['rolename'];
				$roleid[] = $r['id'];
			}
		}
		//获取权限列表
		$user['roleid'] = $roleid;
		$user['rolename'] =  $rolename;
		if(in_array(1, $user['roleid']))$user['priv'] = array();
		else $user['priv'] = $this->AdminRolePrivDao->getRolePriv($user['roleid']);
		return $this->success($user);
	}
	
	
	/**
	 * @todo 权限检查
	 */
	public function checkPriv($user,$controller='',$action='',$whitePriv=array()){
		//白名单
		if( in_array($controller.'|'.$controller, $whitePriv)) return $this->success('通过');
		if(in_array(1,$user['roleid']))return $this->success('通过');
		$priv = $this->adminMenuDao->fetchOne(array(
				'where'=>array('controller'=>$controller,'action'=>$action)
		));
		if(empty($priv))return $this->error('非法操作');
		$check = $this->checkMenuPriv($priv['id'],$user);
		if(!$check)return $this->error('您没有权限操作该项');
		return $this->success('通过');
	}
	
	
	/**
	 * 检测id是否在权限列表里面
	 * @param unknown $id
	 */
	public function checkMenuPriv($id,$user){
		if(in_array($id, $user['priv'])){
			return true;
		}
		return false;
	}
	
	
	
	
		
}
?>
