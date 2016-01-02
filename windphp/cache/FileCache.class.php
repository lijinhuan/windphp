<?php
/*
 * windphp v1.0
* https://github.com/lijinhuan
*
* Copyright 2015 (c) 543161409@qq.com
* GNU LESSER GENERAL PUBLIC LICENSE Version 3
* http://www.gnu.org/licenses/lgpl.html
*
*/

if(!defined('FRAMEWORK_PATH')) {
	exit('access error !');
}


class FileCache implements CacheInterface  {
	
	private $conf;
	
	
	public function __construct($conf){
		$this->conf = $conf;
	}
	
	
	public function get($key){
		$file = APP_PATH.'runtime/data/cache/'.$key.'.php';
		if(is_file($file)){
			$data = require $file;
			if($data and is_string($data)){
				$data = json_decode($data,true);
				if($data['expire']==0 or (time()-$data['cache_time']<$data['expire'])){
					return $data['data'];
				}else{
					return false;	
				}
			}else{
				unlink($file);
				return false;
			}
		}else{
			return false;
		}
	}
	
	
	
	public function set($key,$value,$expire=0){
		if(empty($expire))$expire = $this->conf['data_default_cache_time'];
		$dir = APP_PATH.'runtime/data/cache/';
		if(!is_dir($dir)){
			if(!mkdir($dir,0777)){
				return false;
			}
		}
		$file = $dir.$key.'.php';
		$data = array();
		$data['cache_time'] = time();
		$data['expire'] = $expire;
		$data['data'] = $value;
		$str = '<?php return $str=\'' .json_encode($data)."'; ?>";
		return file_put_contents($file,$str);
	}
	
	
	public function update($key,$value,$expire=0){
		if(empty($expire))$expire = $this->conf['data_default_cache_time'];
		return $this->set($key, $value,$expire);
	}
	
	
	public function delete($key){
		$file = APP_PATH.'runtime/data/cache/'.$key.'.php';
		if(is_file($file)){
			return @unlink($file);
		}else{
			return true;
		}
	}
	
	
}

?>