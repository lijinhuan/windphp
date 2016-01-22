<?php
class Logger {
	
	public static $file_pre = 'log_';
	public static $log_type = 'normal';
	
	public static  function log($msg,$file=''){
		if(is_array($msg))$msg = json_encode($msg);
		if(empty($file)){
			$file = date('Ymd');
		}
		$file = APP_PATH.'logs/'.self::$file_pre.$file.'.txt';
		$msg =  '['.self::type.']'.'['.date('Y-m-d H:i:s').'] '.$msg."\n";
		file_put_contents($file,$msg,FILE_APPEND);
	} 
	
	
}
?>
