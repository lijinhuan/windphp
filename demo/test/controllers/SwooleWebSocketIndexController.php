<?php
/**
 * Copyright (C) windphp framework
 * @todo SwooleHttpIndexController
 */
namespace Controllers;
use Windphp\Web\Request;
use Windphp\Web\Response;
use Windphp\Controller\SwooleController;
use Windphp\Swoole\WebSocket as  SwooleWebSocket;
		
class SwooleWebSocketIndexController extends SwooleController {
	
		public function actionIndex(){
			//方便的数据输入处理
			$id = isset(SwooleWebSocket::$receive_data['id'])?intval(SwooleWebSocket::$receive_data['id']):'';
			//方便的服务调用
			$result = $this->shopService->getShopInfo($id);
			return Response::JsonFormat($result)."\n";
		}
		
		
		public function actionOpen() {
			parent::actionOpen();
			return 'welcome to windphp framework !'."\n";
		}
	
}
?>