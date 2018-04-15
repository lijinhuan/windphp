<?php
/**
 * Copyright (C) windphp framework
 * @todo 
 */
namespace Daos;


use Windphp\Dao\IDao;
class AdminRoleDao extends IDao {
		
	
	public function init(){
		$this->database = 'windphp_admin';
		$this->table = 'role';
	}
	
		
	public function getRole($roleid,$select='*'){
		if(is_string($roleid)){
			$roleid = explode(',', $roleid);
		}
		return $this->fetchAll(array(
				'where'=>array('id'=>array('in'=>$roleid)),
				'select' => $select
		));
	}
	
}
?>
