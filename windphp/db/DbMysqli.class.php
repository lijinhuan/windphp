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


class DbMysqli implements DbInterface  {
	public $user_test_db = false;
	public $select_db = true;
	public $conf;
	public $show_error = true;
	public $error_info = '';
	
	public function __construct($conf){
		$this->conf = $conf;
	}
	
	public function __get($var){
		if($var=='mysqliLink'){
			if(isset($this->mysqliLink)){
				return $this->mysqliLink;
			}
			$this->mysqliLink = $this->connect($this->conf['host'], $this->conf['username'], $this->conf['password'], $this->conf['database'], $this->conf['_charset']);
			return $this->mysqliLink;
		}
	}
	
	
	public function getLink(){
		return $this->mysqliLink;
	}
	
	
	public function connect($host,$username,$password,$database,$_charset){
		@list($dbhost, $port)  = explode(":", $host, 2);
		if (!isset($port)) {
			$port = ini_get("mysqli.default_port");
		} else {
			$options = array(
					'min_range' => 1,
					'max_range' => 65535
			);
			if (function_exists('filter_var') and filter_var($port, FILTER_VALIDATE_INT, $options) === FALSE) {
				throw new Exception($this->conf['database'].' mysqli illegal port range');
			}
		}
		if($this->user_test_db){
			$mysqliLink = @mysqli_connect($dbhost, $username, $password, '', $port);
		}else{
			$mysqliLink = @mysqli_connect($dbhost, $username, $password, $database, $port);
		}
		if ((!$mysqliLink or $mysqliLink->connect_error) and $this->show_error) {
			Logger::log($database.' mysql connect error','mysql_error');
			throw new Exception($this->conf['database'].' mysqli connect error '.$mysqliLink->connect_error);
		}else{
			if(!$mysqliLink or $mysqliLink->connect_error){
				Logger::log($database.' mysql connect error','mysql_error');
				$this->error_info = isset($mysqliLink->connect_error)?$mysqliLink->connect_error:'mysql connect error';
				return false;
			}
		}
		if($this->user_test_db) $this->select_db = mysqli_select_db($mysqliLink, $database);
		mysqli_set_charset($mysqliLink, $_charset);
		return $mysqliLink;
	}
	
	public function switchDb($database){
		return mysqli_select_db($this->mysqliLink, $database);
	}
	
	
	public function query($sql, $link = NULL) {
		empty($link) && $link = $this->mysqliLink;
		$store_sql = ((defined('DEBUG') && DEBUG) or (defined('TRACE') && TRACE)) && isset($_SERVER['sqls']) && count($_SERVER['sqls']) < 1000;
		if($store_sql){
			$start_time = microtime(true);	
		}
		$result = mysqli_query($link,$sql);
		if($store_sql){
			$_SERVER['sqls'][] = stripslashes($sql).'  (<font color="red">'.(microtime(true)-$start_time).' 秒</font>)';
		}
		if(!$result) {
			throw new Exception("Error:". mysqli_error($link). "   (<font color=blue>".$sql."</font>)");
		}
		return $result;
	}
	
	
	public function fetchAssoc($re){
		return mysqli_fetch_assoc($re);
	}
	
	
	
	
	
	/**
	 * 获取一条数据返回数组
	 */
	public function fetchOne($table,$data=array()){
		$data['limit'] = 1;
		$sql = $this->__formatSql($table,$data);
		$re = $this->query($sql);
		$data = mysqli_fetch_assoc($re);
		if(!$data){
			return array();
		}
		return $data;
	}
	
	
	/**
	 * 获取所有结果集
	 */
	function fetchAll($table='',$data=array()){
		$sql = $this->__formatSql($table, $data);
		$res = $this->query($sql);
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
	
	
	
	public function update($table='',$data=array()){
		if(empty($data['where']) && empty($data['limit'])){
			throw new Exception("update all please use limit ！");
		}
		$sql = $this->__formatSql($table, $data,'UPDATE');
		$re =  $this->query($sql);
		if($re){
			$affected = mysqli_affected_rows($this->mysqliLink);
			if($affected>0){
				return $affected;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}
	
	
	public function delete($table='',$data=array()){
		if(empty($data['where']) && empty($data['limit'])){
			throw new Exception("delete all please use limit ！");
		}
		$sql = $this->__formatSql($table, $data,'DELETE');
		return $this->query($sql);
	}
	
	
	public function autocommit($auto=true){
		return mysqli_autocommit($this->mysqliLink,$auto);
	}
	
	
	public function commit(){
		return mysqli_commit($this->mysqliLink);
	}
	
	
	public function rollback(){
		return mysqli_rollback($this->mysqliLink);
	}
	
	
	public function beginTransaction(){
		if(function_exists('mysqli_begin_transaction')){
			return mysqli_begin_transaction($this->mysqliLink,MYSQLI_TRANS_START_READ_WRITE);
		}else{
			return $this->query("START TRANSACTION");	
		}
	}
	
	
	
	public function insert($table='',$data=array()){
		$sql = $this->__formatSql($table, $data,'INSERT');
		$re = $this->query($sql);
		if($re){
			$lastid =  mysqli_insert_id($this->mysqliLink);
			if($lastid){
				return $lastid;
			}else{
				return $re;
			}
		}else{
			return false;
		}
	}
	
	
	public function replace($table='',$data=array()){
		$sql = $this->__formatSql($table, $data,'REPLACE');
		return $this->query($sql);
	}
	
	
	public function version(){
		return mysqli_get_client_info($this->mysqliLink);
	}
	
	public function close(){
		if(isset($this->mysqliLink) and $this->mysqliLink){
			mysqli_close($this->mysqliLink);
		}
	}
	
	public function __destruct(){
		$this->close();
	}
	
	private function __formatSql($table,$data,$type='SELECT'){
		if(empty($table)){
			throw new Exception("sql table empty ！");
		}
		$where = (isset($data['where']) and !empty($data['where']))?' WHERE '.$this->__formatWhere($data['where']):'';
		$set = (isset($data['set']) and !empty($data['set']))?' '.$this->__formatSet($data['set']):'';
		$limit = (isset($data['limit']) and !empty($data['limit']))?' LIMIT '.$data['limit']:'';
		$group = (isset($data['group']) and !empty($data['group']))?' GROUP BY `'.$data['group'].'`':'';
		$order = (isset($data['order']) and !empty($data['order']))?' ORDER BY '.$data['order']:'';
		$having = (($group && isset($data['having'])) and !empty($data['having']))?' HAVING '.$data['having']:'';
		$select = 	(isset($data['select']) and !empty($data['select']))?$data['select']:'*';	
		$sql = '';
		switch (strtoupper($type)){
			case 'SELECT':
				$sql = "SELECT $select FROM $table $where $group  $having $order $limit";
				break;
			case 'UPDATE':
				if(empty($set)){throw new Exception("update set error table:$table ！");} 
				$sql = "UPDATE $table  SET   $set $where  $limit";
				break;
			case 'DELETE':
				$sql = "DELETE FROM $table $where $limit";
				break;
			case 'INSERT':
				$sql = "INSERT INTO $table SET $set";
				break;
			case 'REPLACE':
				$sql = "REPLACE INTO $table SET $set";
				break;
		}
		if(empty($sql)){
			throw new Exception("sql $type not support ！");
		}
		return $sql;
	}
	
	
	private function __formatSet($keys){
		$set = '';
		$c = ' , ';
		foreach ($keys as $k=>$v){
			
			
			if(is_int($v) and strlen($v)<10){
				$set .= ' `'.$k.'`='.intval($v).$c;
			}elseif (is_array($v)){
				if(empty($v)){
					$v = '';
					$set .= ' `'.$k.'`=""'.$c;
				}else{
					if(isset($v['count'])){
						if(strpos($v['count'], '-')){
							$count = '-'.rtrim($v['count'],'-');
						}else{
							$count = '+'.rtrim($v['count'],'+');
						}
						$set .= $k.'='.$k.$count.$c;
					}elseif(isset($v['b'])){
						$set .= $k."=b'".$v['b']."'".$c;
					}
				}
			}else{
				$set .= ' `'.$k.'`='."'" . mysqli_real_escape_string($this->mysqliLink,$v) . "'".$c;
			}
		}
		return rtrim($set,$c);
	}
	
	
	private function __formatWhere($keys=array()){
		$where = '';
		$c = ' and ';
		foreach ($keys as $k=>$v){
			if(is_int($v) and strlen($v)<10){
				$where .= ' `'.$k.'`='.intval($v).$c;
			}elseif(is_array($v)){
				if(empty($v)){
					$v = '';
					$where .= ' `'.$k.'`='."''".$c;
				}else{
					if(isset($v['in'])){
						$varr = array();
						foreach ($v['in'] as  $imval){
							$varr[] = mysqli_real_escape_string($this->mysqliLink,$imval);
						}
						$v = "'".implode("','", $varr)."'";
						$where .= ' `'.$k.'` in('.$v.')'.$c;
					} 
					if(isset($v['like'])){
						$where .= ' `'.$k.'` like \''.mysqli_real_escape_string($this->mysqliLink,$v['like']).'\'' .$c;
					}
					if(isset($v['gt'])){
					
						$value = is_int($v['gt'])?$v['gt']:intval($v['gt']);
						$where .= ' `'.$k.'` > '.$value . $c;
					} 
					if(isset($v['gte'])){
						
						$value = is_int($v['gte'])?$v['gte']:intval($v['gte']);
						$where .= ' `'.$k.'` >= '.$value . $c;
					}
					if(isset($v['lt'])){
						
						$value = is_int($v['lt'])?$v['lt']:intval($v['lt']);
						$where .= ' `'.$k.'` < '.$value . $c;
					}
					if(isset($v['lte'])){
						
						$value = is_int($v['lte'])?$v['lte']:intval($v['lte']);
						$where .= ' `'.$k.'` <= '.$value . $c;
					}
					if(isset($v['neq'])){
						if($v['neq']==''){
							$value = "''";
						}else{
							
							$value = is_int($v['neq'])?$v['neq']:intval($v['neq']);
						}
						
						$where .= ' `'.$k.'` != '.$value . $c;
					}
					if(empty($where)){
						$where .= ' 1 ' .$c;
					}
				}
			}else{
				$where .= ' `'.$k.'`='."'" . mysqli_real_escape_string($this->mysqliLink,$v) . "'".$c;
			}
		}
		return rtrim($where,$c);
	}
	
	
	
	
	
}
?>
