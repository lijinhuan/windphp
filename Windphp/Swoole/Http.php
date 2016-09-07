<?php
/**
 * @todo Http
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Swoole;
use Windphp\Windphp;

class Http {
	private static $controller_prefix = 'SwooleHttp';
	public static $request = array();
	
    public static function Run($host,$port,$set=array()) {
    	$serv = new \swoole_http_server($host, $port);
    	$serv->set($set);
    	$serv->on('Request', function($request, $response) {
    		self::$request = $request;
    		$response->header("X-Server","Swoole");
    		$response->header("X-Powered-By:","Windphp");
    		$response->header("Expires","0");
    		$response->header("Cache-Control","private, post-check=0, pre-check=0, max-age=0");
    		$response->header("Pragma","no-cache");
    		$response->header("Content-Type","text/html; charset=UTF-8");
    		$controller = (isset($request->get) and isset($request->get['controller']))?htmlspecialchars($request->get['controller']):'Index';
    		$action = (isset($request->get) and isset($request->get['action']))?htmlspecialchars($request->get['action']):'Index';
    		$controller = self::$controller_prefix.ucfirst($controller);
    		$result =  Windphp::runController($controller,$action,true,false);
    		$response->end($result);
    	});
    	$serv->start();
    }
    
}
?>
