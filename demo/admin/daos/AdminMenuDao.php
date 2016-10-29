<?php
/**
 * Copyright (C) windphp framework
 * @todo adminUserDao
 */
namespace Daos;


use Windphp\Dao\IDao;
class AdminMenuDao extends IDao {
		
	
	public function init(){
		$this->database = 'windphp_admin';
		$this->table = 'menu';
	}
	
	public function getTopId($id){
		if(empty($id))return 0;
		return $this->_getTopId($id);
	}
	
	
	private function _getTopId($id){
		$r = $this->fetchOne(array('where' => array('id'=>$id),'select'=>'id,parentid'));
		if(empty($r['parentid'])){return $r['id'];}
		$return = 0;
		if($r['parentid']) {
			$return = $this->_getTopId($r['parentid']);
		}
		return $return;
	}
	
	public function getMenuActionName($parentid){
		$data = $this->fetchAll(array('where'=>array('parentid'=>$parentid)));
		if(!empty($data)){
			$num = 0;
			foreach ($data as $val){
				$order = intval(ltrim(trim($val['action']),'class2_'));
				if($order>$num){
					$num = $order;
				}
			}
			if($num>0){
				return 'class2_'.($num+1);
			}
		}
		return 'class2_1';
	}
	
	
	
	public function getCurrentPos($id){
		$r = $this->fetchOne(array('where' => array('id'=>$id),'select'=>'id,name,parentid'));
		if(empty($r)){return '';}
		$str = '';
		if($r['parentid']) {
			$str = $this->getCurrentPos($r['parentid']);
		}
		return $str.$r['name'].' > ';
	}
	
	
	
	public  function getMenuList($parentid,$id){
		$tree = new \Components\TreeComponet();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$result = $this->fetchAll(array(
				'where'=>array('level'=>array('lt'=>4),'id'=>array('neq'=>$id)),
				'order' => 'listorder DESC,id DESC'
		));
		$array = array();
		foreach($result as $r) {
			$r['cname'] = $r['name'];
			$r['selected'] = $r['id'] == $parentid ? 'selected' : '';
			$array[] = $r;
		}
		$str  = "<option value='\$id' \$selected>\$spacer \$cname</option>";
		$tree->init($array);
		$categorys = $tree->get_tree(0, $str);
		return $categorys;
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
