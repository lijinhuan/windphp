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
class CController extends IController {
	
   
	public function __destruct() {
		if(Config::getSystem('debug')>1 || Config::getSystem('trace')) {
			\Windphp\Core\Debug::show(array('config'=>Config::$systemConfig,'server'=>$_SERVER));
		}
	}
	
}