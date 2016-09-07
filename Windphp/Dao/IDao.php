<?php
/**
 * @todo Dao基类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Dao;


use Windphp\Core\Config;
use Windphp\Windphp;
use Windphp\Db\DbFactory;
class IDao {
	
	public  $table;
	public  $database;
	public  $conf;
	
	
	function __construct() {
		$this->conf = Config::$systemConfig;
		if(method_exists($this, 'init')) {
			$this->init();
		}
	}
	
	
	/**
	 * @todo 获取数据库实例
	 */
	public function getDb(){
		if(empty($this->database)){
			throw new \Exception("dao database not select !");
		}
		if(!isset($this->conf['db'][$this->database])) {
			throw new \Exception($this->database." database not exists !");
		}
		$this->database = lcfirst($this->database);
		return DbFactory::getDb($this->conf['db'][$this->database]);
	}

	
	public function fetchOne($data){
		return $this->getDb()->fetchOne($this->table,$data);
	}
	
	
	public function fetchAll($data){
		return $this->getDb()->fetchAll($this->table,$data);
	}
	
	
	/**
	 * @todo 合并插入
	 * $field = array('id','name');
	 * $values = array(
	 * 		array(1,'jinhuan.li'),
	 * 		array(1,'test'),
	 * );
	 * @return boolean
	 */
	public function insertArray($filed,$values){
		return $this->insertArray($filed,$values);
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
	
	
	public function queryBySql($sql){
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
	
	
	public function getSqls() {
		return $this->getDb()->getSqls();
	}

    
	public function getVersion() {
		return $this->getDb()->version();
	}
	
	
	//事务
	
	
	public function beginTransaction() {
		return $this->getDb()->beginTransaction();
	}
	
	
	public function commit() {
		return $this->getDb()->commit();
	}
	
	
	public function autocommit($auto=true) {
		return $this->getDb()->autocommit($auto);
	}
	
	
	public function rollback() {
		return $this->getDb()->rollback();
	}
	
}
