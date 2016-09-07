<?php
/**
 * @todo WebSocket
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Swoole;
use Windphp\Windphp;

class WebSocket {
	
	private static $controller_prefix = 'SwooleWebSocket';
	public static $receive_data = array();
	public static $serv;
	public static $current_fd;
	public static $request;
	public static $frame;
	
    public static function Run($host,$port,$set=array()) {
    	$serv = new \swoole_websocket_server($host, $port);
    	$serv->set($set);
    	$serv->on('open', function($server, $req) {
    		 self::$serv = $server;
    		 self::$current_fd = $req->fd;
    		 self::$request = $req;
    		 $result = Windphp::runController(self::$controller_prefix.ucfirst('Index'),'Open',true,false);
    		 $server->push($req->fd, $result);
    	});
    	$serv->on('message', function($server, $frame) {
    		self::$frame = $frame;
    		self::$receive_data = @json_decode($frame->data,true);
    		$controller = isset(self::$receive_data['controller'])?htmlspecialchars(self::$receive_data['controller']):'Index';
    		$action = isset(self::$receive_data['action'])?htmlspecialchars(self::$receive_data['action']):'Index';
    		$controller = self::$controller_prefix.ucfirst($controller);
    		$result =  Windphp::runController($controller,$action,true,false);
    		$server->push($frame->fd, $result);
    	});
    	$serv->on('close', function($server, $fd) {
    		echo "connection close: ".$fd;
    	});
    	$serv->start();
    }
    
}
?>
