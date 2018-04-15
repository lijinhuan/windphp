<?php
/**
 * Copyright (C) windphp framework
 * @todo CommonController
 */
namespace Controllers;
use Windphp\Controller\CController;
use Windphp\Core\UrlRoute;
use Windphp\Web\Request;
use Windphp\Misc\ShowPage;
use Windphp\Web\Response;
use Windphp\Windphp;


class CommonController extends CController {
		
	protected  $controllerAction = array('Login|Run','Login|Out','Login|Submit','Api|Checkcode');//登录验证白名单
	protected   $whitePriv = array('Index|Run','Index|Index','Index|Left','Index|Main','Index|CurrentPos');//权限验证白名单
	protected  $_user = array();//用户信息
	protected  $_uid = 0;//用户uid
	protected $_refer = '';
	protected $_page = '';
	protected  $_cmodel = '';
	
	
	
	function __construct() {
		$this->_refer = Request::getRefer();
		$this->_page = ShowPage::getCurPage();
		//安装检测
		$this->installService->checkInstall();
		$controller_action = UrlRoute::$current_controller.'|'.UrlRoute::$current_action;
		if(!in_array($controller_action, $this->controllerAction)){
			$check = $this->userService->checkLogin();
			if(!$check) {
				Response::showMessage('请先登录',UrlRoute::getWebUrl('Login-Run'));
			}
			//获取登录用户信息
			$login_user = $this->userService->getLoginUser();
			if($login_user['code']<1){
				Response::showMessage($login_user['msg'],UrlRoute::getWebUrl('Login-Run'));
			}else{
				$this->_user = $login_user['data'];
				$this->_uid = $this->_user['id'];
				unset($this->_user['password']);
			}
			$checkRole = $this->rolePrivService->initCheckRole($this->_user);
			if($checkRole['code']<1){
				Response::showMessage($checkRole['msg'],UrlRoute::getWebUrl('Login-Run'));
			}else{
				$this->_user = $checkRole['data'];
			}
			$check_priv = $this->rolePrivService->checkPriv($this->_user,UrlRoute::$current_controller,UrlRoute::$current_action,$this->whitePriv);
			if($check_priv['code']<1){
				Response::showMessage($check_priv['msg'],'blank');
			}
			$this->addOptLog();
			$this->tpl->assign('user',$this->_user);
			$this->tpl->assign('controller',UrlRoute::$current_controller);
			$this->tpl->assign('action',UrlRoute::$current_action);
			$this->tpl->assign('refer',$this->_refer);
			$this->tpl->assign('current_page',$this->_page);
		}
	}
	
	
	
	
	
	protected function addOptLog(){
		if(in_array(UrlRoute::$current_controller.'|'.UrlRoute::$current_action, $this->controllerAction))return;
		if( in_array(UrlRoute::$current_controller.'|'.UrlRoute::$current_action, $this->whitePriv)) return;
		$json_post = json_encode($_POST);
		if(strpos($json_post,'password')!==false){
			$json_post = '[]';
		}
		$set = array(
				'uid' => $this->_uid,
				'controller' => UrlRoute::$current_controller,
				'action' => UrlRoute::$current_action,
				'get' => json_encode($_GET),
				'post' => $json_post,
				'file' => json_encode($_FILES),
				'ip' => Request::getIp(),
				'addtime' => $_SERVER['time']
		);
		Windphp::getDao('adminOptLog')->insert(array('set'=>$set));
	}
		
	
	
	
	private function checkCmodel(){
		if(!is_object($this->_cmodel)){
			Response::showMessage('please define $this->_cmodel','blank');
		}
	
	}
	
	
	/**
	 * @todo 公共删除方法
	 */
	public function actionCdel(){
		$this->checkCmodel();
		$id = intval(Request::getInput('id','int'));
		empty($id) and Response::showMessage('param error!',$this->_refer);
		$param = array('where'=>array('id'=>$id));
		if(method_exists($this,'beforDelEvent')){
			$this->beforDelEvent($id);
		}
		$re = $this->_cmodel->delete($param);
		if($re){
			if(method_exists($this,'afterDelEvent')){
				$this->afterDelEvent($id,$re);
			}
			Response::showMessage('成功删除',$this->_refer);
		}else{
			Response::showMessage('成功失败',$this->_refer);
		}
	}
	
	
	/**
	 * @todo 公共添加方法
	 */
	public function actionCadd(){
		$this->checkCmodel();
		if(Request::getInput('doAddSubmit')){
			$info = Request::getInput('info','string',false);
			if(method_exists($this,'beforAddEvent')){
				$info = $this->beforAddEvent($info);
			}
			$insert_id = $this->_cmodel->insert(array('set'=>$info));
			if($insert_id){
				if(method_exists($this,'afterAddEvent')){
					$info = $this->afterAddEvent($info,$insert_id);
				}
				Response::showMessage('成功添加',$this->_refer);
			}else{
				Response::showMessage('添加失败',$this->_refer);
			}
		}else{
			if(method_exists($this,'addDataEvent')){
				$this->addDataEvent();
			}
			$this->tpl->show();
		}
	}
	
	
	/**
	 * @todo 公共修改方法
	 */
	public function actionCedit(){
		$this->checkCmodel();
		$id = Request::getInput('id','int');
		if(Request::getInput('doEditSubmit')){
			$info = Request::getInput('info','string',false);
			$info['id'] = $id;
			
			if(method_exists($this,'beforEditEvent')){
				$info = $this->beforEditEvent($info);
			}
			
			$re=$this->_cmodel->update(array('set'=>$info,'where'=>array('id'=>$id)));
			if($re){
				
				if(method_exists($this,'afterEditEvent')){
					$info = $this->afterEditEvent($info,$re);
				}
				Response::showMessage('成功修改',$this->_refer);
			}else{
				Response::showMessage('修改失败',$this->_refer);
			}
		}else{
			$data = $this->_cmodel->fetchOne(array('where'=>array('id'=>$id)));
			if(method_exists($this,'editDataEvent')){
				$data = $this->editDataEvent($data);
			}
			$this->tpl->assign('data',$data);
			$this->tpl->show();
		}
	}
	
	
}
?>