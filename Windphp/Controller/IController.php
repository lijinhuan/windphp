<?php
/**
 * @todo 控制器基础类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */
namespace Windphp\Controller;


use Windphp\Core\Config;
use Windphp\Windphp;
class IController {

	
	public function __get($var) {
		if($var=='tpl'){
			return Windphp::getView();
		}else if(substr($var, -7)==='Service') {
			$service = substr($var,0, -7);
			return Windphp::getService($service);	
		}
	}
	
   
}