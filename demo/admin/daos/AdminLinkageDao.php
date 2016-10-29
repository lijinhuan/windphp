<?php
/**
 * Copyright (C) windphp framework
 * @todo 
 */
namespace Daos;


use Windphp\Dao\IDao;
class AdminLinkageDao extends IDao {
		
	
	public function init(){
		$this->database = 'windphp_admin';
		$this->table = 'linkage';
	}
	
	
	
	
	public  function getAllSubMenu($id){
		if(empty($id))return array();
		$id_arr =  $this->_getAllSubMenu($id);
		return $id_arr;
	}
	
	
	private function _getAllSubMenu($id){
		$arr = array();
		$sub = $this->fetchAll(array(
				'where' => array('parentid'=>$id)
		));
		if($sub){
			$arr[] = $id;
			foreach ($sub as $s){
				$result = $this->_getAllSubMenu($s['id']);
				$arr = array_merge($result,$arr);
			}
		}else{
			$arr[] = $id;
		}
		return $arr;
	}
		
}
?>
