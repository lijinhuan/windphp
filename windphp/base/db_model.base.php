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
  
class db_model  {
	public  $table;
	public  $dbtag;
	public  $conf;
	public  $no_cache = false;
	
	function __construct($conf) {
		$this->conf = $conf;
	}
	
	
	public function getDb(){
		if(empty($this->dbtag)){
			throw new Exception("dbtag empty !");
		}
		$dbconf = $this->conf['db'][$this->dbtag];
		return  core::db($dbconf);
	}
	
	
	public function __get($var){
		if($var=='file'){
			return core::cache($var,$this->conf);
		}elseif(substr($var,-3,3)=='_db'){
			$var_arr = explode("_", $var);
			$dbconf = $this->conf['db'][$var_arr[0]];
			return  core::db($dbconf);
		}
		$var_arr = explode("_", $var);
		
		$count = count($var_arr);
		if($count<2){
				throw new Exception("$var error ！");
		}
		
		if(in_array($var_arr[0], $this->conf['support_cache'])){
			return core::cache($var_arr[0],$this->conf,$var_arr[1]);
		}else{
			$table = substr($var,strlen($var_arr[0].'_'));
			return core::model($var_arr[0],$table,$this->conf);
		}
		
	}
	
	
	public function getCache(){
		return core::cache($this->conf['cache_type'],$this->conf);
	}
	
	
	public function fetch_one($data=array()){
		return $this->getDb()->fetch_one($this->table,$data);
	}
	
	
	public function fetch_all($data=array()){
		return $this->getDb()->fetch_all($this->table,$data);
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
		$key = $this->table.'_'.misc::implode_multiArr($cache_key,'-');
		$cache_key = strlen($key)<32?$key:md5($key);
		return $cache_key;
	}
	
	
	public function cache_fetch_one($data=array(),$emptyWrite=true){
		if(isset($data['cache_key'])){
			$cache_key = $data['cache_key'];
		}else{
			$cache_key = $this->formatKey($data);
		}
		$result = $this->getCache()->get($cache_key);
		if($result===false or $this->no_cache){
			if(isset($data['cache_time'])){
				$cache_time = $data['cache_time'];
			}else{
				$cache_time = $this->conf['data_default_cache_time'];
			}
			$result = $this->getDb()->fetch_one($this->table,$data);
			if((empty($result) and $emptyWrite) or !empty($result)){
				$this->getCache()->set($cache_key,$result,$cache_time);
			}
		}
		return $result;
	}
	
	
	public function cache_fetch_all($data=array(),$emptyWrite=true){
		if(isset($data['cache_key'])){
			$cache_key = $data['cache_key'];
		}else{
			$cache_key = $this->formatKey($data);
		}
		$result = $this->getCache()->get($cache_key);
		if($result===false or $this->no_cache){
			if(isset($data['cache_time'])){
				$cache_time = $data['cache_time'];
			}else{
				$cache_time = $this->conf['data_default_cache_time'];
			}
			$result = $this->getDb()->fetch_all($this->table,$data);
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