<?php
/**
 * @todo 异常类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Core;
use Windphp\Web\Request;
use Windphp\Core\Config;


class CException  {
	
	
	public static  function exceptionHandle($e) {
		$log = $msg = "[msg] : {$e->getMessage()}; [file] : {$e->getFile()}; [line] : {$e->getLine()}; [code] : {$e->getCode()}";
		$debug = false;
		if(Config::getSystem('debug')<1) {
			$debug = true;
			$msg = htmlspecialchars($e->getMessage());
		}
		if(Config::$systemConfig['log_err']) {
			Logger::log($msg,Logger::ERR,'throw_exception');
		}
		if(Request::isCli() || ($debug and !Request::isAjax())){
			exit(trim($msg,"\t")."\n");
		}else{
			if(Request::isAjax()) {
				exit(json_encode(array('status'=>$status,'msg'=>$msg)));
			}else {
				echo "<html><head><title>错误提示</title></head><body>";
				echo '<div style="padding:20px;border:2px solid red;background:#e1e1e1;margin:10px;border-radius:10px;">';
				echo "<font color=green><b>Message:</b></font> ".htmlspecialchars($e->getMessage())."<br/>";
				echo "<font color=green><b>File:</b></font> " .  $e->getFile()."<br/>";
				echo "<font color=green><b>Line: </b></font>" . $e->getLine()."<br/>";
				echo "<font color=green><b>Code: </b></font>" . $e->getCode()."<br/>";
				echo "<pre>";
				print_r($e->getTrace());
				echo "</pre>";
				echo "<font color=green><b>PowerBy: </b></font>windphp framework<br/>";
				echo '</div></body></html>';
				exit();
			}
		}		
	}
	
	
}
