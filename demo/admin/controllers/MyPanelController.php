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
class MyPanelController extends CommonController {
		
	
	/**
	 * @todo 修改我的信息
	 */
	public function actionModifyInfo(){
		if(Request::getInput('doEditSubmit')){
			$info = Request::getInput('info');
			if(empty($info['realname']) or empty($info['email']) or empty($info['phone']) or empty($info['qq'])){
				Response::showMessage('所有项必填！',$this->_refer);
			}
			$re =  Windphp::getDao('adminUser')->update(array(
					'set'=>array(
							'realname' => $info['realname'],
							'email' => $info['email'],
							'phone' => $info['phone'],
							'qq' => $info['qq']
					),
					'where' => array('id'=>$this->_uid)
			));
			if($re){
				Response::showMessage('成功修改！',$this->_refer);
			}else{
				Response::showMessage('修改失败！',$this->_refer);
			}
		}
		$info =  $this->userService->mergeExtend(array($this->_user));
		if($info){$info=$info[0];}
		$this->tpl->assign('info',$info);
		$this->tpl->show();
	}
	
	
	/**
	 * @todo 修改密码
	 */
	public function actionModifyPassword(){
		if(Request::getInput('dosubmit','string')){
			$info = Request::getInput('info','string',false);
			if(empty($info['password']) or empty($info['new_password']) or empty($info['renew_password'])){
				Response::showMessage('所有项必填！',$this->_refer);
			}
			$user =  Windphp::getDao('adminUser')->fetchOne(array('where'=>array('id'=>$this->_uid)));
			$user_password = md5($user['salt'].Config::getSystem('autokey').$info['password']);
			if($user_password !=$user['password']){
				Response::showMessage('密码不正确',$this->_refer);
			}
			if($info['new_password']!=$info['renew_password']){
				Response::showMessage('两次密码不一致',$this->_refer);
			}
			$salt = Utils::createRandomstr();
			$update['salt'] = $salt;
			$update['password'] =  md5($salt.Config::getSystem('autokey').$info['new_password']);
			$re =  Windphp::getDao('adminUser')->update(array(
					'set' => $update,
					'where' => array('id'=>$this->_uid)
			));
			if($re){
				Response::showMessage('成功修改！',UrlRoute::getWebUrl('Login-Out'));
			}else{
				Response::showMessage('修改失败！',$this->_refer);
			}
		}
		$this->tpl->show();
	}
	
	
}
?>