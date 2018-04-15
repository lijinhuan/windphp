<?php
/**
 * Copyright (C) windphp framework
 * @todo 
 */
namespace Controllers;

use Windphp\Windphp;		
use Windphp\Web\Response;
use Windphp\Core\UrlRoute;
use Windphp\Web\Request;
use Windphp\Core\Config;
use Windphp\Misc\Utils;
class DepartmentController extends CommonController {
		
	
	function __construct() {
		parent::__construct();
		$this->_cmodel = Windphp::getDao('department');
	}
	
	
	public function actionRun(){
		$tree = new \Components\TreeComponet();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$result = Windphp::getDao('department')->fetchAll(array());
		foreach ($result as $key=>$val){
			$delete_url = UrlRoute::getWebUrl('Department-Cdel-id-'.$val['id']);
			$edit_url = UrlRoute::getWebUrl('Department-Cedit-id-'.$val['id']);
			$result[$key]['str_manage'] = "<a href=\"javascript:edit('修改{$val['name']}','{$edit_url}')\"> 修改 </a> |";
			$result[$key]['str_manage'] .= "<a href=\"javascript:confirmurl('{$delete_url}','确定要删除吗')\"> 删除 </a>";
		}
		$str  = "<tr>
					<td align='center'>\$id</td>
					<td >\$spacer\$name</td>
					<td align='center'>\$str_manage</td>
				</tr>";
		$tree->init($result);
		$categorys = $tree->get_tree(0, $str);
		$this->tpl->assign('categorys',$categorys);
		$this->tpl->show();
	}
	
	
	public function addDataEvent(){
		$department = $this->_cmodel->getSelectList();
		$this->tpl->assign('department',$department);
	}
	
	
	public function beforAddEvent($info){
		$info['level'] = 1;
		if($info['parentid']){
			$parent =  $this->_cmodel->fetchOne(array('where'=>array('id'=>$info['parentid'])));
			if($parent){
				$info['level'] = $parent['level']+1;
			}
		}
		return $info;
	}
	
	
	public function beforDelEvent($id){
		//是否有下级菜单
		$dep_check =  $this->_cmodel->fetchOne(array('where'=>array('parentid'=>$id)));
		$dep_check and Response::showMessage('请先删除下级菜单',$this->_refer);
		//判断是否有人属于该部门，如果部门不为空则不行
		
		$user_check = Windphp::getDao('adminUser')->fetchOne(array('where'=>array('department_id'=>$id)));
		
		$user_check and Response::showMessage('该部门下有正在工作的用户，无法删除',$this->_refer);
	}
	
	
	
}
?>
