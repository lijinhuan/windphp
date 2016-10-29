<?php
/**
 * Copyright (C) windphp framework
 * @todo 首页
 */
namespace Controllers;

		
use Windphp\Web\Response;
use Windphp\Core\UrlRoute;
use Windphp\Web\Request;
class LoginController extends CommonController {
		
	/**
	 * @todo 登录首页
	 */
	public function actionRun(){
		
		$this->tpl->show();
	}
	
	
	
	/**
	 * @todo 登录提交
	 */
	public function actionSubmit(){
		$username = Request::getInput('username','string');
		$password =  Request::getInput('password','string');
		$checkcode =  Request::getInput('code','string');
		
		if(empty($username) or empty($password) or empty($checkcode)){
			Response::showMessage('所有项必填',Request::getRefer());
		}
		$check_login = $this->userService->doLogin($username,$password,$checkcode);
		if($check_login['code']<1) {
			Response::showMessage($check_login['msg'],Request::getRefer());
		}else{
			$url =  Request::getInput('refer','string');
			if($url){
				Response::showMessage('成功登录！',urldecode($url));
			}else{
				Response::showMessage('成功登录！',UrlRoute::getWebUrl('Index-Run'));
			}
		}
	}
		
	
	/**
	 * @todo 退出登录
	 */
	public function actionOut(){
		$this->userService->loginOut();
		Response::showMessage('成功退出！',UrlRoute::getWebUrl('Login-Run'));
	}
	
}
?>