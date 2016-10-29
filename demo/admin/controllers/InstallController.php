<?php
/**
 * Copyright (C) windphp framework
 * @todo 安装
 */
namespace Controllers;

		
use Windphp\Controller\CController;
use Windphp\Web\Response;
use Windphp\Core\UrlRoute;
use Windphp\Web\Request;
class InstallController extends CController  {
		
	
	/**
	 * @todo 安装首页
	 */
	public function actionRun(){
		switch ($this->installService->checkStatus()) {
			case 1 :
				Response::showMessage('该页面不存在',UrlRoute::getWebUrl('Index-Run'));
				break;
			case 2 :
				Response::showMessage('请完善管理员信息',UrlRoute::getWebUrl('Install-Manager'));
				break;
		}
		if(Request::getInput('doSubmit','string')){
			$dbhost = Request::getInput('dbhost','string');
			$dbname = Request::getInput('dbname','string');
			$dbuser = Request::getInput('dbuser','string');
			$dbpassword = Request::getInput('dbpassword','string');
			$conf = array('host'=>$dbhost,'username'=>$dbuser,'password'=>$dbpassword,'database'=>$dbname,'_charset'=>'utf8');
			foreach ($conf as $k=>$v){
				if($k=='password')continue;
				if(empty($v))Response::showMessage($k.'不能为空！',Request::getRefer());
			}
			$install_db = $this->installService->initDatabase($conf);
			if($install_db['code']<1) {
				Response::showMessage($install_db['msg'],Request::getRefer());
			}else{
				Response::showMessage('下一步填写管理员帐户密码',UrlRoute::getWebUrl('Install-Manager'));
			}
		}
		$this->tpl->show();
	}
	
	
	
	/**
	 * @todo 填写基础信息
	 */
	public function actionManager(){
		$check_install  = $this->installService->checkStatus();
		if($check_install==1) {
			Response::showMessage('该页面不存在',UrlRoute::getWebUrl('Index-Run'));
		}
		if($check_install==0) {
			Response::showMessage('请先填写数据库信息',UrlRoute::getWebUrl('Install-Run'));
		}
		if(Request::getInput('doAddSubmit','string')){
			$info = Request::getInput('info','string',false);
			foreach ($info as $key=>$val){
				empty($val) and Response::showMessage($key.'项必填！',Request::getRefer());
			}
			if($info['password']!=$info['repassword']){
				Response::showMessage('两次密码不一致',Request::getRefer());
			}
			unset($info['repassword']);
			$result = $this->installService->initUser($info);
			if($result['code']<1) {
				Response::showMessage($result['msg'],Request::getRefer());
			}else{
				Response::showMessage('成功安装，请先登录管理后台',UrlRoute::getWebUrl('Login-Run-t-'.time()));
			}
		}
		$this->tpl->show();
	}
	
	
		
}
?>