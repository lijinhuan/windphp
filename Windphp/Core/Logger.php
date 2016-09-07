<?php
/**
 * @todo web url 日志类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Core;


use Windphp\Windphp;
use Windphp\Misc\FileDir;
class Logger {
	
	// 日志级别 从上到下，由低到高
	const EMERG  = 'EMERG'; // 严重错误: 导致系统崩溃无法使用
	const ALERT  = 'ALERT'; // 警戒性错误: 必须被立即修改的错误
	const CRIT   = 'CRIT'; // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
	const ERR    = 'ERR'; // 一般错误: 一般性错误
	const WARN   = 'WARN'; // 警告性错误: 需要发出警告的错误
	const NOTICE = 'NOTIC'; // 通知: 程序可以运行但是还不够完美的错误
	const INFO   = 'INFO'; // 信息: 程序输出信息
	const DEBUG  = 'DEBUG'; // 调试: 调试信息
	const SQL    = 'SQL'; // SQL：SQL语句 注意只在调试模式开启时有效
	
	public static $suffixes = '.log';
	public static $log_file_size = 2097152;
	
	
	public static  function log($msg,$level=self::DEBUG,$file=''){
		$now = date('Y-m-d H:i:s');
		if(is_array($msg))$msg = json_encode($msg);
		if(empty($file)){$file = date('Y_m_d');}
		$file = Windphp::getLogsPath().$file.self::$suffixes;
		if(!is_dir(dirname($file))) {
			if(!FileDir::mkdir(dirname($file))) {
				return false;
			}
		}
		//检测日志文件大小，超过配置大小则备份日志文件重新生成
		if (is_file($file) && self::$log_file_size <= filesize($file)) {
			rename($file, dirname($file) . '/' . time() . '-' . basename($file));
		}
		$msg =  "{$level}: {$msg}\r\n";
		$remote_addr = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'';
		$request_uri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
		error_log("[{$now}] " . $remote_addr . ' ' . $request_uri . "\n{$msg}", 3, $file);
	}
	
	
	
	
	
}