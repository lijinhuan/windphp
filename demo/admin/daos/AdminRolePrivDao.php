<?php
/**
 * Copyright (C) windphp framework
 * @todo adminUserDao
 */
namespace Daos;


use Windphp\Dao\IDao;
class AdminRolePrivDao extends IDao {
		
	
	public function init(){
		$this->database = 'windphp_admin';
		$this->table = 'role_priv';
	}
	
	
	public function getRolePriv($roleid,$select="*"){
		if(!is_array($roleid)){
			$roleid = explode(',', $roleid);
		}
		$data = $this->fetchAll(array(
				'where'=>array('id'=>array('in'=>$roleid)),
				'select' => $select
		));
		if($data){
			$priv = array();
			foreach ($data as $d){
				$d_arr = json_decode($d['priv'],true);
				$priv = array_merge($d_arr,$priv);
			}
			return array_unique($priv);
		}else{
			return array();
		}
	}
	
	
	public function checkPriv($roleid, $id){
		$data = $this->getRolePriv($roleid);
	
		if(in_array($id, $data)){
			return ' checked ';
		}
		return '';
	}
		
}
?>
