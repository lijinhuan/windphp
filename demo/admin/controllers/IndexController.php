<?php
/**
 * Copyright (C) windphp framework
 * @todo 首页
 */
namespace Controllers;

		
use Windphp\Windphp;
use Windphp\Web\Request;
class IndexController extends CommonController {
		
	
		/**
		 * @todo 后台框架页
		 */
		public function actionRun(){
			$top_menu = $this->adminMenu(0);
	
			$index = true;
			$this->tpl->assign('index',$index);
			$this->tpl->assign('top_menu',$top_menu);
			$this->tpl->show();
		}
	
	
		/**
		 * @todo 后台首页
		 */
		public function actionMain(){
			$mysql_version = Windphp::getDao('adminMenu')->getDb()->version();
			$this->tpl->assign('mysql_version',$mysql_version);
			$this->tpl->show();
		}
	
	
		/**
		 * @todo 获取当前位置
		 */
		public function actionCurrentPos(){
			$str = Windphp::getDao('adminMenu')->getCurrentPos(Request::getInput('menuid','int'));
			exit($str);
		}
	
	
		/**
		 * @todo 左侧
		 */
		public function actionLeft(){
			$menuid = Request::getInput('menuid','int');
			$datas = $this->adminMenu($menuid);
			if (isset($_GET['parentid']) && $parentid = intval($_GET['parentid']) ? intval($_GET['parentid']) : 10) {
				foreach($datas as $_value) {
					if($parentid==$_value['id']) {
						echo '<li id="_M'.$_value['id'].'" class="on top_menu"><a href="javascript:_M('.$_value['id'].',\'index.php?controller='.$_value['controller'].'&action='.$_value['action'].'\')" hidefocus="true" style="outline:none;">'.$_value['name'].'</a></li>';
	
					} else {
						echo '<li id="_M'.$_value['id'].'" class="top_menu"><a href="javascript:_M('.$_value['id'].',\'index.php?controller='.$_value['controller'].'&action='.$_value['action'].'\')"  hidefocus="true" style="outline:none;">'.$_value['name'].'</a></li>';
					}
				}
			} else {
				foreach ($datas as  $key => $_value){
					$sub_array = $this->adminMenu($_value['id']);
					if($sub_array){
						foreach ($sub_array as $_k=>$_m){
							$data = $_m['data'] ? '&'.$_m['data'] : '';
							$url = "?controller={$_m['controller']}&action={$_m['action']}{$data}";
							$sub_array[$_k]['url'] = $url;
						}
					}
					$datas[$key]['sub_array'] = $sub_array;
				}
				$this->tpl->assign('datas',$datas);
				$this->tpl->show();
			}
		}
	
	
	
	
		/**
		 * 按父ID查找菜单子项
		 * @param integer $parentid   父菜单ID
		 * @param integer $with_self  是否包括他自己
		 */
		protected function adminMenu($parentid, $with_self = 0) {
			$parentid = intval($parentid);
			$result = Windphp::getDao('adminMenu')->fetchAll(array(
					'where' => array('parentid'=>$parentid,'display'=>1),
					'order' => 'listorder DESC',
					'limit' => 1000
			));
			if($with_self) {
				$result2[] =  Windphp::getDao('adminMenu')->fetchOne(array('where' => array('id'=>$parentid)));
				$result = array_merge($result2,$result);
			}
			//权限检查
			if(in_array(1, $this->_user['roleid']))return $result;
			$array = array();
			foreach ($result as $v){
				$controller_action = $v['controller'].'|'.$v['action'];
				if(in_array($controller_action, $this->whitePriv)) {
					$array[] = $v;
				} else {
					$check = $this->rolePrivService->checkMenuPriv($v['id'],$this->_user);
					if($check) $array[] = $v;
				}
			}
			return $array;
		}
	
	
	
	
		
}
?>