<?php
/**
 * Copyright (C) windphp framework
 * @todo 
 */
namespace Daos;


use Windphp\Dao\IDao;
class DepartmentDao extends IDao {
		
	
	public function init(){
		$this->database = 'windphp_admin';
		$this->table = 'department';
	}
	
	public  function getSelectList($id=0){
		$tree = new \Components\TreeComponet();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$result = $this->fetchAll(array(
				'where'=>array(),
				'order' => 'id DESC'
		));
	
		$array = array();
		foreach($result as $r) {
			$r['cname'] = $r['name'];
			$r['selected'] = $r['id'] == $id ? 'selected' : '';
			$array[] = $r;
		}
		$str  = "<option value='\$id' \$selected>\$spacer \$cname</option>";
		$tree->init($array);
		$categorys = $tree->get_tree(0, $str);
		return $categorys;
	}
	
	
		
}
?>

