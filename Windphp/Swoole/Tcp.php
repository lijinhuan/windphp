<?php
/**
 * @todo Tcp
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Swoole;
use Windphp\Windphp;

class Tcp {
	
	private static $controller_prefix = 'SwooleTcp';
	public static $receive_data = array();
	public static $current_fd;
	public static $serv;
	
	
    public static function Run($host,$port,$set=array()) {
    	$serv = new \swoole_server($host, $port);
    	$serv->set($set);
    	$serv->on('connect', function ($serv, $fd){
    		self::$serv = $serv;
    		self::$current_fd = $fd;
    		$result = Windphp::runController(self::$controller_prefix.ucfirst('Index'),'Open',true,false);
    		$serv->send($fd, $result);
    	});
    	$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    		self::$receive_data  = @json_decode($data,true);
    		$controller = isset(self::$receive_data['controller'])?htmlspecialchars(self::$receive_data['controller']):'Index';
    		$action = isset(self::$receive_data['action'])?htmlspecialchars(self::$receive_data['action']):'Index';
    		$controller = self::$controller_prefix.ucfirst($controller);
    		$result =  Windphp::runController($controller,$action,true,false);
    		$serv->send($fd,$result);
    		//$serv->close($fd);
    	});
    	$serv->on('close', function ($serv, $fd) {
    		echo "connection close: ".$fd;
    	});
    	$serv->start();
    }
    
}
?>
