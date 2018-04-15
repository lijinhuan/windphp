<?php
/**
 * Copyright (C) windphp framework
 * @todo 
 */
namespace Daos;


use Windphp\Dao\IDao;
class AdminOptLogDao extends IDao {
		
	
	public function init(){
		$this->database = 'windphp_admin';
		$this->table = 'user_opt_log';
	}
	
		
	
	
}
?>
