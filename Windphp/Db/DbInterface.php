<?php
/**
 * @todo Db驱动接口
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-9-03
 */

namespace Windphp\Db;


interface DbInterface {

	public function __construct($dbConf);

	// 获取版本
	public function version();

	public function fetchOne($table,$data);
	
	public function fetchAll($table,$data);
	
	public function update($table,$data);
	
	public function delete($table,$data);
	
	public function insert($table,$data);
	
	public function insertArray($filed,$values);
	
	public function replace($table,$data);
	
	public function getSqls();
	
	public function query($sql, $link);
	
	public function fetchAssoc($re);
	
	public function beginTransaction();
	
	public function commit();
	
	public function autocommit();
	
	public function rollback();
}



?>