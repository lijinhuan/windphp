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
	public  $table;
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
		return Core::setMagicGet($var, $this->conf);
	}
	
	
	public function getCache(){
		return Core::cache($this->conf['cache_type'],$this->conf);
	}
	
	
	public function fetchOne($data=array()){
		if(!is_array($data)){
			$re = $this->query($data);
			$row = mysqli_fetch_assoc($re);
			if(!$row){
				return array();
			}
			return $row;
		}
		return $this->getDb()->fetchOne($this->table,$data);
	}
	
	
	public function fetchAll($data){
		if(!is_array($data)){
			$res = $this->query($data);
			if ($res !== false){
				$arr = array();
				while ($row = mysqli_fetch_assoc($res)){
					$arr[] = $row;
				}
				return $arr;
			}else{
				return array();
			}
		}
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
	
	
	public function fetchAssoc($re){
		return $this->getDb()->fetchAssoc($re);
	}
	
	
	public function count($where=array()){
		$count = $this->getDb()->fetchOne($this->table,array(
				'select' => 'COUNT(*) as count',
				'where' => $where
		));
		$count = isset($count['count'])?$count['count']:0;
		return $count;
	}
	
}
?>