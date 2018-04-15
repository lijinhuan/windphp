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
use Windphp\Misc\ShowPage;
class MenuController extends CommonController {
		
	
	
	/**
	 * @todo 菜单列表
	 */
	public function actionList(){
		$this->menuid = intval(Request::getInput('menuid'));
		$top_id = intval(Request::getInput('top_id'));
		$parent = array();
		if($top_id){
			$parent = Windphp::getDao('adminMenu')->fetchOne(array(
					'where' => array('id'=>$top_id)
			));
			empty($parent) and Response::showMessage('该菜单不存在',UrlRoute::getWebUrl('Menu-List'));
		}
		if(isset($_POST['dosubmit'])){
			$listorders = Request::getInput('listorders','string',false);
			if($listorders){
				foreach ($listorders as $id=>$order){
					Windphp::getDao('adminMenu')->update(array(
							'set' => array('listorder'=>$order),
							'where' => array('id'=>$id)
					));
				}
			}
			Response::showMessage('成功修改排序',UrlRoute::getWebUrl('Menu-List'));
		}
		$tree = new \Components\TreeComponet();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
	
		$fetch_query = array(
				'where' => array('level'=>array('in'=>array(1))),
				'order' => 'listorder DESC,id DESC',
				'limit' => 1000,
		);
		if($top_id){unset($fetch_query['where']);}
	
		$result = Windphp::getDao('adminMenu')->fetchAll($fetch_query);
		$array = array();
		foreach($result as $r) {
			$url = '';
			$r['str_manage'] = '';
			if(empty($top_id)){
				$top_str = "&top_id=".$r['id'];
				$url = UrlRoute::getWebUrl('Menu-List-top_id-'.$r['id']);
				$r['str_manage'] .= '  <a href="'.$url.'">查看下级菜单</a>  | ';
			}
			$r['cname'] = $r['name'];
			if($r['level']<4){
				$url = UrlRoute::getWebUrl('Menu-Add-parentid-'.$r['id'].'-menuid-'.$this->menuid);
				$r['str_manage']  .= '  <a href="'.$url.'">添加子菜单</a>  | ';
			}
			$url = UrlRoute::getWebUrl('Menu-Edit-id-'.$r['id'].'-menuid-'.$this->menuid);
			$r['str_manage'] .= '  <a href="'.$url.'">修改</a>   |  ';
			$url = UrlRoute::getWebUrl('Menu-Delete-id-'.$r['id'].'-menuid-'.$this->menuid);
			$url = "javascript:confirmurl('{$url}','确认要{$r['name']}删除吗')";
			$r['str_manage'] .= '  <a href="'.$url.'">删除</a>  ';
			$array[] = $r;
		}
		$str  = "<tr>
					<td align='center'><input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input-text-c'></td>
					<td align='center'>\$id</td>
					<td >\$spacer\$cname</td>
					<td align='center'>\$str_manage</td>
				</tr>";
		$tree->init($array);
		$categorys = $tree->get_tree($top_id, $str);
	
		$this->tpl->assign('parent',$parent);
		$this->tpl->assign('categorys',$categorys);
		$this->tpl->show();
	}
	
	
	/**
	 * @todo 删除菜单
	 */
	public function actionDelete(){
		$id = intval(Request::getInput('id'));
		empty($id) and Response::showMessage('参数有误',$this->_refer);
		$id_arr = Windphp::getDao('adminMenu')->getAllSubMenu($id);
		$re = Windphp::getDao('adminMenu')->delete(array(
				'where' => array('id'=>array('in'=>$id_arr))
		));
		if($re){
			Response::showMessage('成功删除',$this->_refer);
		}else{
			Response::showMessage('删除失败',$this->_refer);
		}
	}
	
	
	
	/**
	 * @todo 添加菜单
	 */
	public function actionAdd(){
		if(Request::getInput('dosubmit')){
			$info = Request::getInput('info','string',false);
			$parentid = $info['parentid'];
			empty($info['name']) and Response::showMessage('菜单名称不能为空',$this->_refer);
			$parent = Windphp::getDao('adminMenu')->fetchOne(array(
					'where' => array('id'=>$parentid)
			));
			if(empty($parent)){
				$info['controller'] = 'top_'.\Components\HelperComponet::toPinyin($info['name']);
				$info['action'] = 'class1';
			}else{
				$info['controller'] = isset($info['controller'])?trim($info['controller']):$parent['controller'];
				if(ucfirst($parent['action'])=='Class1'){
					$info['action'] = Windphp::getDao('adminMenu')->getMenuActionName($parentid);
				}
				if($parent['level']>1){
					if(empty($info['controller'])){
						Response::showMessage('controller不能为空',$this->_refer);
					}
				}
			}
				
			$info['controller'] = ucfirst($info['controller']);
			$info['action'] = ucfirst($info['action']);
			$re = Windphp::getDao('adminMenu')->insert(array('set'=>$info));
			if($re){
				$parent_id = empty($parent)?0:$parent['parentid'];
				$top_id =  Windphp::getDao('adminMenu')->getTopId($parent_id);
				$url = UrlRoute::getWebUrl('Menu-List-top_id-'.$top_id);
				Response::showMessage('成功添加',$url);
			}else{
				Response::showMessage('添加失败',$this->_refer);
			}
		}
		$parentid = intval(Request::getInput('parentid'));
		$parent = Windphp::getDao('adminMenu')->fetchOne(array(
				'where' => array('id'=>$parentid)
		));
		if(empty($parent)){
			$level = 1;
			$parent['controller'] = $parent['action'] = '';
		}else{
			$level = $parent['level']+1;
		}
		if($level>4){
			Response::showMessage('该菜单不能再添加子菜单',$this->_refer);
		}
		$parent['name'] = isset($parent['name'])?trim($parent['name']):'作为一级菜单';
		$this->tpl->assign('parent',$parent);
		$this->tpl->assign('level',$level);
		$this->tpl->assign('parentid',$parentid);
		$this->tpl->show();
	}
	
	
	/**
	 * @todo 修改菜单
	 */
	public function actionEdit(){
		$id = intval(Request::getInput('id'));
		$menu = $parent = Windphp::getDao('adminMenu')->fetchOne(array('where' => array('id'=>$id)));
		empty($menu) and Response::showMessage('菜单不存在',$this->_refer);
		if(Request::getInput('dosubmit')){
			$info = Request::getInput('info','string',false);
			if(isset($info['parentid']) and $info['parentid']!=$menu['parentid']){
				$check = Windphp::getDao('adminMenu')->fetchOne(array('where' => array('parentid'=>$id)));
				!empty($check) and Response::showMessage('移动所属菜单，请先删除清空下属菜单',$this->_refer);
				$p = Windphp::getDao('adminMenu')->fetchOne(array('where' => array('id'=>$info['parentid'])));
				$info['level'] = $p['level']+1;
			}
			$re = Windphp::getDao('adminMenu')->update(array(
					'where'=>array('id'=>$id),
					'set'=>$info
			));
			if($re){
				Response::showMessage('成功修改',$this->_refer);
			}else{
				Response::showMessage('修改失败',$this->_refer);
			}
		}
		$menu_list = Windphp::getDao('adminMenu')->getMenuList($menu['parentid'],$id);
	
		$top_id =  Windphp::getDao('adminMenu')->getTopId($menu['parentid']);
		$url = UrlRoute::getWebUrl('Menu-List-top_id-'.$top_id);
		$this->tpl->assign('back_url',$url);
		$this->tpl->assign('menu_list',$menu_list);
		$this->tpl->assign('menu',$menu);
		$this->tpl->show();
	}
	
	
	
	
}
?>