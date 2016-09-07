<?php
/**
 * Copyright (C) windphp framework
 * @todo SwooleHttpIndexController
 */
namespace Controllers;
use Windphp\Web\Request;
use Windphp\Web\Response;
use Windphp\Controller\SwooleController;
use Windphp\Swoole\Http as  SwooleHttp;
		
class SwooleHttpIndexController extends SwooleController {
	
		public function actionIndex(){
			//方便的数据输入处理
			$id = isset(SwooleHttp::$request->get['id'])?intval(SwooleHttp::$request->get['id']):'';
			//方便的服务调用
			$result = $this->shopService->getShopInfo($id);
			return Response::JsonFormat($result);
		}
	
}
?>