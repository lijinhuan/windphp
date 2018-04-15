<?php
/**
 * Copyright (C) windphp framework
 * @todo adminUserDao
 */
namespace Daos;


use Windphp\Dao\IDao;
class AdminUserDao extends IDao {
		
	
	public function init(){
		$this->database = 'windphp_admin';
		$this->table = 'user';
	}
	
	
	
		
}
?>
