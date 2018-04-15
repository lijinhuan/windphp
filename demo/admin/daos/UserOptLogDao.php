<?php
/**
 * Copyright (C) windphp framework
 * @todo adminUserDao
 */
namespace Daos;


use Windphp\Dao\IDao;
class UserOptLogDao extends IDao {
		
	
	public function init(){
		$this->database = 'windphp_admin';
		$this->table = 'user_opt_log';
	}
	
	
	
		
}
?>
