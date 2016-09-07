<?php
/**
 * Copyright (C) windphp framework
 * @todo IndexController
 */
namespace Controllers;
use Windphp\Web\Response;
use Windphp\Controller\CliController;
use Windphp\Windphp;


class CliIndexController extends CliController {
		
	public function actionIndex(){
		$id = isset(Windphp::$argv[3])?intval(Windphp::$argv[3]):0;
		//方便的服务调用
		$result = $this->shopService->getShopInfo($id);
		exit(Response::JsonFormat($result)."\n");
	}
		
}
?>