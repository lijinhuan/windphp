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
  
class DbModel  {
	public  $dbTag;
	public  $conf;
	public  $noCache = false;
	
	
	function __construct($conf) {
		$this->conf = $conf;
	}
	
	
	/**
	 * @todo 获取数据库实例
	 */
	public function getDb(){
		if(empty($this->dbTag)){
			throw new Exception("dbtag empty !");
		}
		$this->dbTag = lcfirst($this->dbTag);
		return Core::db($this->conf['db'][$this->dbTag]);
	}
	
	
	public function __get($var){
		if($var=='file'){
			return Core::cache($var,$this->conf);
		}elseif(substr($var,-3,3)=='_db'){
			$var_arr = explode("_", $var);
			$dbconf = $this->conf['db'][$var_arr[0]];
			return  Core::db($dbconf);
		}
		$var_arr = explode("_", $var);
		$count = count($var_arr);
		if($count<2){
			throw new Exception("$var error ！");
		}
		if(in_array($var_arr[0], $this->conf['support_cache'])){
			return Core::cache($var_arr[0],$this->conf,$var_arr[1]);
		}else{
			$table = substr($var,strlen($var_arr[0].'_'));
			return Core::model($var_arr[0],$table,$this->conf);
		}
	}
	
	
	public function getCache(){
		return Core::cache($this->conf['cache_type'],$this->conf);
	}
	
	
	public function fetchOne($data=array()){
		return $this->getDb()->fetchOne($this->table,$data);
	}
	
	
	public function fetchAll($data=array()){
		return $this->getDb()->fetchAll($this->table,$data);
	}
	
	
	public function update($data=array()){
		return $this->getDb()->update($this->table,$data);
	}
	
	
	public function delete($data=array()){
		return $this->getDb()->delete($this->table,$data);
	}
	
	
	public function insert($data=array()){
		return $this->getDb()->insert($this->table,$data);
	}
	
	public function replace($data=array()){
		return $this->getDb()->replace($this->table,$data);
	}
	
	
	public function formatKey($data=array()){
		$cache_key = array_merge(array_keys($data),$data);
		$key = $this->table.'_'.Misc::implodeMultiArr($cache_key,'-');
		$cache_key = strlen($key)<32?$key:md5($key);
		return $cache_key;
	}
	
	
	public function cacheFetchOne($data=array(),$emptyWrite=true){
		if(isset($data['cache_key'])){
			$cache_key = $data['cache_key'];
		}else{
			$cache_key = $this->formatKey($data);
		}
		$result = $this->getCache()->get($cache_key);
		if($result===false or $this->noCache){
			if(isset($data['cache_time'])){
				$cache_time = $data['cache_time'];
			}else{
				$cache_time = $this->conf['data_default_cache_time'];
			}
			$result = $this->getDb()->fetchOne($this->table,$data);
			if((empty($result) and $emptyWrite) or !empty($result)){
				$this->getCache()->set($cache_key,$result,$cache_time);
			}
		}
		return $result;
	}
	
	
	public function cacheFetchAll($data=array(),$emptyWrite=true){
		if(isset($data['cache_key'])){
			$cache_key = $data['cache_key'];
		}else{
			$cache_key = $this->formatKey($data);
		}
		$result = $this->getCache()->get($cache_key);
		if($result===false or $this->noCache){
			if(isset($data['cache_time'])){
				$cache_time = $data['cache_time'];
			}else{
				$cache_time = $this->conf['data_default_cache_time'];
			}
			$result = $this->getDb()->fetchAll($this->table,$data);
			if((empty($result) and $emptyWrite) or !empty($result)){
				$this->getCache()->set($cache_key,$result,$cache_time);
			}
		}
		return $result;
	}
	
	
	public function query($sql){
		return $this->getDb()->query($sql);
	}
	
	
}
?>