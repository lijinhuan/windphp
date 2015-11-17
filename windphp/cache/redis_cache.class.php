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


class redis_cache implements cache_interface  {
	private  $redis = '';
	
	
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
		$this->redis = $this->connect($server);
	}
	
	
	public function getObj(){
		return $this->redis;
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
		return $this->redis->get($key);
	}
	
	
	
	public function set($key,$value,$expire=0){
		return $this->redis->Setex($key,$expire,$value);
	}
	
	
	public function update($key,$value,$expire=0){
		return $this->redis->Setex($key,$expire,$value);
	}
	
	
	public function delete($key){
		return $this->redis->delete($key);
	}
	
	
	public function hExists($cacheKey,$key){
		return $this->redis->hExists($cacheKey,$key);
	}
	
	
	public function hIncrBy($cacheKey,$key,$count){
		return $this->redis->hIncrBy($cacheKey,$key,$count);
	}
	
	
	public function hget($cacheKey,$field){
		return $this->redis->hget($cacheKey,$field);
	}
	
	
	public function hset($cacheKey,$field,$result){
		return $this->redis->hset($cacheKey,$field,$result);
	}
	
	public function expire($cacheKey, $cacheTime){
		return $this->redis->expire($cacheKey, $cacheTime);
	}
	
	
	public function hgetall($cacheKey){
		return $this->redis->hgetall($cacheKey);
	}
	
	public function hmset($cacheKey,$data){
		return $this->redis->hmset($cacheKey,$data);
	}
	
	
	public function incr($cacheKey){
		return $this->redis->incr($cacheKey);
	}
	
	public function decr($cacheKey){
		return $this->redis->decr($cacheKey);
	}
	
	
	public function hDel($cacheKey,$field){
		return $this->redis->hDel($cacheKey,$field);
	}
	
	
	public function lpush($cacheKey,$data){
		return $this->redis->lpush($cacheKey,$data);
	}
	
	
	public function rpop($cacheKey){
		return $this->redis->rpop($cacheKey);
	}
	
	
}

?>