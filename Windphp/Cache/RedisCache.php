<?php
/**
 * @todo redis缓存
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Cache;


use Windphp\Core\Config;
class RedisCache implements CacheInterface  {
	private  $__redis = '';
	public  $machine = '';

	
	public function __construct($machine){
		$this->machine = $machine;
		if($this->machine){
			if(!isset( Config::$systemConfig['redis'][$this->machine])) {
				throw new \Exception('redis '.$this->machine." not exists");
			}
			$server = Config::$systemConfig['redis'][$this->machine]['servers'];
		}else{
			$server = array();
			foreach (Config::getSystem('redis') as $c){
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
		$redis = new \Redis();
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
		$result =  $this->__redis->get($key);
		if(strpos($result, '"redis_tag":"windphp_redis_arr"')!==false){
			$data = json_decode($result, TRUE);
			if(isset($data['redis_tag']) and $data['redis_tag']=='windphp_redis_arr'){
				return $data['data'];
			}
		}
		return $result;
	}
	
	
	
	public function set($key,$value,$expire=0){
		if(is_array($value)){
			$data['redis_tag'] = 'windphp_redis_arr';
			$data['data'] = $value;
			$value = json_encode($data);
		}
		if(empty($expire))$expire = Config::getSystem('data_default_cache_time');
		return $this->__redis->Setex($key,$expire,$value);
	}
	
	
	public function update($key,$value,$expire=0){
		if(is_array($value)){
			$data['redis_tag'] = 'windphp_redis_arr';
			$data['data'] = $value;
			$value = json_encode($data);
		}
		if(empty($expire))$expire = Config::getSystem('data_default_cache_time');
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
	
	
	public function hLen($cacheKey){
		return $this->__redis->hlen($cacheKey);
	}
	
}

?>