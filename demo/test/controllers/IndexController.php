<?php
/**
 * Copyright (C) windphp framework
 * @todo IndexController
 */
namespace Controllers;
use Windphp\Controller\CController;
use Windphp\Web\Request;
use Windphp\Web\Response;
use Windphp\Misc\ShowPage;
use Windphp\Core\Config;
use Windphp\Misc\HttpClient;
use components\TestComponet;
		
class IndexController extends CController {
		
	public function actionIndex(){
		//方便的数据输入处理
		$input_param = array('from','id');
		$input_param_type = array('string','int'); 
		$input_param = Request::getInput($input_param,$input_param_type);
		//方便的服务调用
		$result = $this->shopService->getShopInfo($input_param['id']);
		if($result['code']<1) {
			exit(Response::JsonFormat($result));
		}
		//方便的数据输出处理
		$this->tpl->assign('data',$result['data']);
		$this->tpl->show();
	}
	
	
	public function actionTestHttp() {
		echo HttpClient::get('www.baidu.com', 's?wd=client');
	}
	
	
	public function actionTestPage() {
			$page = max(Request::getInput('page','int'),1);
			echo ShowPage::getPageStr(660, $page,false);
	}
	
	public function actionTestComponent() {
		TestComponet::test();
	}
	
	public function actionWebSocket() {
		
		$this->tpl->show();
	}
		
}
?>