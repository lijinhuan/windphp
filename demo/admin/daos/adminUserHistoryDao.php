<?php
/**
 * Copyright (C) windphp framework
 * @todo 
 */
namespace Daos;


use Windphp\Dao\IDao;
class AdminUserHistoryDao extends IDao {
		
	
	public function init(){
		$this->database = 'windphp_admin';
		$this->table = 'user_history';
	}
	
		
	
	
}
?>

