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


class RedisCache implements CacheInterface  {
	private  $__redis = '';
	
	
	public function __construct($conf){
		if(isset($conf['cache_flag'])){
			$server = $conf['redis'][$conf['cache_flag']]['servers'];
		}else{
			if(empty($conf)){
				throw new Exception("redis conf empty!");
			}
			$server = array();
			foreach ($conf['redis'] as $c){
				$server = $c['servers'];
				break;
			}
		}
		$this->__redis = $this->connect($server);
	}
	
	
	public function getObj(){
		return $this->__redis;
	}
	
	
	private function connect($server){
		$redis = new Redis();
		if ($redis->connect($server['host'],$server['port'],$server['timeout']) == false) {
			die($redis->getLastError());
		}
		if(!empty($server['auth'])){
			$auth = $server['auth'];
			if ($redis->auth($auth['user'] . ":" . $auth['password']) == false) {
				die($redis->getLastError());
			}
		}
		return $redis;
	}
	
	
	public function get($key){
		return $this->__redis->get($key);
	}
	
	
	
	public function set($key,$value,$expire=0){
		if(empty($expire))$expire = $this->conf['data_default_cache_time'];
		return $this->__redis->Setex($key,$expire,$value);
	}
	
	
	public function update($key,$value,$expire=0){
		if(empty($expire))$expire = $this->conf['data_default_cache_time'];
		return $this->__redis->Setex($key,$expire,$value);
	}
	
	
	public function delete($key){
		return $this->__redis->delete($key);
	}
	
	
	public function hExists($cacheKey,$key){
		return $this->__redis->hExists($cacheKey,$key);
	}
	
	
	public function hIncrBy($cacheKey,$key,$count){
		return $this->__redis->hIncrBy($cacheKey,$key,$count);
	}
	
	
	public function hGet($cacheKey,$field){
		return $this->__redis->hget($cacheKey,$field);
	}
	
	
	public function hSet($cacheKey,$field,$result){
		return $this->__redis->hset($cacheKey,$field,$result);
	}
	
	public function expire($cacheKey, $cacheTime){
		return $this->__redis->expire($cacheKey, $cacheTime);
	}
	
	
	public function hGetAll($cacheKey){
		return $this->__redis->hgetall($cacheKey);
	}
	
	public function hMset($cacheKey,$data){
		return $this->__redis->hmset($cacheKey,$data);
	}
	
	
	public function incr($cacheKey){
		return $this->__redis->incr($cacheKey);
	}
	
	public function decr($cacheKey){
		return $this->__redis->decr($cacheKey);
	}
	
	
	public function hDel($cacheKey,$field){
		return $this->__redis->hDel($cacheKey,$field);
	}
	
	
	public function lPush($cacheKey,$data){
		return $this->__redis->lpush($cacheKey,$data);
	}
	
	
	public function rPop($cacheKey){
		return $this->__redis->rpop($cacheKey);
	}
	
	
}

?>