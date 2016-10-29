<?php
/**
 * Copyright (C) windphp framework
 * @todo 
 */
namespace Controllers;

use Windphp\Windphp;		
use Windphp\Web\Response;
use Windphp\Core\UrlRoute;
use Windphp\Web\Request;
use Windphp\Core\Config;
use Windphp\Misc\Utils;
use Windphp\Misc\ShowPage;
class AdministratorsController extends CommonController {
		
	
	function __construct() {
		parent::__construct();
		$this->_cmodel = Windphp::getDao('adminUser');
	}
	
	
	public function actionRun(){
		$total_count = Windphp::getDao('adminUser')->count();
		$result = Windphp::getDao('adminUser')->fetchAll(array(
				'limit' => ShowPage::getPageQueryLimit(),
				'order' => 'id desc'
		));
		$result = $this->userService->mergeExtend($result);
		
		$page = ShowPage::getPageStr($total_count, $this->_page,true,Config::getSystem('page_rows'));
		$this->tpl->assign('total_count',$total_count);
		$this->tpl->assign('page',$page);
		$this->tpl->assign('result',$result);
		$this->tpl->show();
	}
	
	
	public function beforDelEvent($id){
		$data = $this->_cmodel->fetchOne(array('where'=>array('id'=>$id)));
		if($data){
			$re = Windphp::getDao('adminUserHistory')->insert(array('set'=>$data));
			if(!$re){
				Response::showMessage('备份失败，请稍后操作',$this->_refer);
			}
		}
	}
	
	
	public function addDataEvent(){
		$this->addEditDataAssign();
	}
	
	
	public function editDataEvent($data){
		$this->addEditDataAssign($data);
		$data['roleid'] = explode(',', $data['roleid']);
		return $data;
	}
	
	
	private function addEditDataAssign($data=array()){
		$roles = Windphp::getDao('adminRole')->fetchAll(array());
		if($data){
			$department = Windphp::getDao('department')->getSelectList(intval($data['department_id']));
		}else{
			$department = Windphp::getDao('department')->getSelectList();
		}
		$this->tpl->assign('department',$department);
		$this->tpl->assign('roles',$roles);
	}
	
	
	public function beforAddEvent($info){
		foreach ($info as $key=>$val){
			empty($val) and Response::showMessage($key.'项必填！',$this->_refer);
		}
		if($info['password']!=$info['repassword']){
			Response::showMessage('两次密码不一致',$this->_refer);
		}
		$check_username = $this->_cmodel->fetchOne(array('where'=>array('username'=>$info['username'])));
		!empty($check_username) and Response::showMessage('用户已经存在，不允许重复添加',$this->_refer);
		unset($info['repassword']);
		$salt = Utils::createRandomstr();
		$info['salt'] = $salt;
		$info['password'] =  md5($salt.Config::getSystem('autokey').$info['password']);
		$roleid = Request::getInput('roleid','string',false);
		empty($roleid) and Response::showMessage('请选择角色！',$this->_refer);
		$info['roleid'] = implode(',', $roleid);
		return $info;
	}
	
	
	public function beforEditEvent($info){
		if($info['password'] or $info['repassword']){
			if($info['password']!=$info['repassword']){
				Response::showMessage('两次密码不一致',$this->_refer);
			}
			$salt = Utils::createRandomstr();
			$info['salt'] = $salt;
			$info['password'] =  md5($salt.Config::getSystem('autokey').$info['password']);
		}else{
			unset($info['password']);
		}
		unset($info['repassword']);
	
		$check_username = $this->_cmodel->fetchOne(array('where'=>array('username'=>$info['username'],'id'=>array('neq'=>$info['id']))));
		!empty($check_username) and Response::showMessage('用户已经存在，不允许重复添加',$this->_refer);
	
	
		foreach ($info as $key=>$val){
			empty($val) and Response::showMessage($key.'不能为空！',$this->_refer);
		}
		$roleid = Request::getInput('roleid','string',false);
		empty($roleid) and Response::showMessage('请选择角色！',$this->_refer);
		$info['roleid'] = implode(',', $roleid);
		return $info;
	}
	
}
?>