<?php
class Logger {
	
	public static $file_pre = 'log_';
	public static $log_type = 'normal';
	
	public static  function log($msg,$file=''){
		if(is_array($msg))$msg = json_encode($msg);
		if(empty($file)){
			$file = date('Ymd');
		}
		$dir = APP_PATH.'logs/';
		$file = $dir.self::$file_pre.$file.'.txt';
		if(!is_dir($dir)){
			if(!mkdir($dir,0777)){
				return false;
			}
		}
		$msg =  '['.self::$log_type.']'.'['.date('Y-m-d H:i:s').'] '.$msg."\n";
		file_put_contents($file,$msg,FILE_APPEND);
	} 
	
	
}
?>
