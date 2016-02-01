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


class MemcacheCache implements CacheInterface  {
	private $__memCache;
	public  $conf = array();
	
	
	public function __construct($conf){
		$this->conf = $conf;
		if(isset($conf['cache_flag'])){
			$configServerArray =  $conf['memd'][$conf['cache_flag']]['servers'];
			$this->__memCache = $this->connect($configServerArray);
		}else{
			if(empty($conf)){
				throw new Exception("memcache conf empty !");
			}
			$memcacheArr = array();
			$use_first = false;
			foreach ($conf['memd'] as $val){
				if(!empty($val['auth'])){
					$use_first = true;		
				}
				$memcacheArr[] = $val;
			}
			if($use_first){
				$this->__memCache = $this->connect($memcacheArr[0]['servers']);
			}else{
				$this->__memCache = new Memcached();
				foreach ($memcacheArr as $configServerArray){
					$this->__memCache = $this->connect($configServerArray['servers']);
				}
			}
		}
	}
	
	
	private function connect($configServerArray){
		$obj = new Memcached();
		$obj->addServer($configServerArray['host'],$configServerArray['port'],$configServerArray['height']);
		if(!empty($configServerArray['auth'])){
			$obj->setOption(Memcached::OPT_COMPRESSION, false); //关闭压缩功能
			$obj->setOption(Memcached::OPT_BINARY_PROTOCOL, true); //使用binary二进制协议
			$auth = $configServerArray['auth'];
			$obj->setSaslAuthData($auth['user'],$auth['password']); //设置OCS帐号密码进行鉴权，如已开启免密码功能，则无需此步骤
		}
		return $obj;
	} 
	
	
	public function get($key){
		return $this->__memCache->get( $key );
	}
	
	
	
	public function set($key,$value,$expire=0){
		if(empty($expire))$expire = $this->conf['data_default_cache_time'];
		return $this->__memCache->set($key,  $value ,$expire);
	}
	
	
	public function update($key,$value,$expire=0){
		if(empty($expire))$expire = $this->conf['data_default_cache_time'];
		return $this->__memCache->set($key,  $value ,$expire);
	}
	
	
	public function delete($key){
		return $this->__memCache->delete( $key );
	}
	
	
	public function increment($key){
		return $this->__memCache->increment($key);
	}
	
	
}
?>