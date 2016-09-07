<?php
/**
 * @todo memcache缓存
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Cache;


use Windphp\Core\Config;
class MemcacheCache implements CacheInterface  {
	private $__memCache;
	public  $machine = '';
	
	
	public function __construct($machine){
		$this->machine = $machine;
		if($this->machine){
			if(!isset(Config::$systemConfig['memd'][$this->machine])){
				throw new \Exception('memcache '.$this->machine." not exists");
			}
			$configServerArray =  Config::$systemConfig['memd'][$this->machine]['servers'];
			$this->__memCache = $this->connect($configServerArray);
		}else{
			$memcacheArr = array();
			$use_first = false;
			foreach (Config::getSystem('memd') as $val){
				if(!empty($val['auth'])){
					$use_first = true;		
				}
				$memcacheArr[] = $val;
			}
			if($use_first){
				$this->__memCache = $this->connect($memcacheArr[0]['servers']);
			}else{
				$this->__memCache = new \Memcached();
				foreach ($memcacheArr as $configServerArray){
					$this->__memCache = $this->connect($configServerArray['servers']);
				}
			}
		}
	}
	
	
	private function connect($configServerArray){
		$obj = new \Memcached();
		$obj->addServer($configServerArray['host'],$configServerArray['port'],$configServerArray['height']);
		if(!empty($configServerArray['auth'])){
			$obj->setOption(\Memcached::OPT_COMPRESSION, false); //关闭压缩功能
			$obj->setOption(\Memcached::OPT_BINARY_PROTOCOL, true); //使用binary二进制协议
			$auth = $configServerArray['auth'];
			$obj->setSaslAuthData($auth['user'],$auth['password']); //设置OCS帐号密码进行鉴权，如已开启免密码功能，则无需此步骤
		}
		return $obj;
	} 
	
	
	public function get($key){
		return $this->__memCache->get( $key );
	}
	
	
	
	public function set($key,$value,$expire=0){
		if(empty($expire))$expire = Config::getSystem('data_default_cache_time');
		return $this->__memCache->set($key,  $value ,$expire);
	}
	
	
	public function update($key,$value,$expire=0){
		if(empty($expire))$expire = Config::getSystem('data_default_cache_time');
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