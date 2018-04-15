<?php
/**
 * Copyright (C) windphp framework
 * @todo role
 */
namespace Controllers;

		
use Windphp\Windphp;		
use Windphp\Web\Response;
use Windphp\Core\UrlRoute;
use Windphp\Web\Request;
use Windphp\Core\Config;
use Windphp\Misc\Utils;
use Windphp\Misc\ShowPage;
class RoleController extends CommonController {
		
	
	
	function __construct() {
		parent::__construct();
		$this->_cmodel = Windphp::getDao('adminRole');
	}
	
	
	/**
	 * @todo 角色列表
	 */
	public function actionRun(){
		$total_count = Windphp::getDao('adminRole')->count();
		$result = Windphp::getDao('adminRole')->fetchAll(array(
				'limit' => ShowPage::getPageQueryLimit(),
				'order' => 'id desc'
		));
		$page = ShowPage::getPageStr($total_count, $this->_page,true,Config::getSystem('page_rows'));
		$this->tpl->assign('total_count',$total_count);
		$this->tpl->assign('page',$page);
		$this->tpl->assign('result',$result);
		$this->tpl->show();
	}
	
	
	/**
	 * @todo 权限设置
	 */
	public function actionPrivSetting(){
		$roleid = intval(Request::getInput('roleid'));
		empty($roleid) and Response::showMessage('参数有误');
		if(Request::getInput('dosubmit')){
			$delete_re = Windphp::getDao('adminRolePriv')->delete(array('where'=>array('id'=>$roleid),'limit'=>1));
			$treeids = Request::getInput('menuid','string',false);
			if($treeids and count($treeids)>0){
				$inId = json_encode($treeids);
				if($inId){
					Windphp::getDao('adminRolePriv')->replace(array('set' => array('id'=>$roleid,'priv'=>$inId)));
				}
			}
			Response::showMessage('成功修改',$this->_refer);
		}
		$tree = new \Components\TreeComponet();
		$tree->icon = array('│ ','├─ ','└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$result = Windphp::getDao('adminMenu')->fetchAll(array('limit'=>1000));
		$priv_data = Windphp::getDao('adminRole')->fetchAll(array('limit'=>1000)); //获取权限表数据
		foreach ($result as $n=>$t) {
			$result[$n]['cname'] = $t['name'];
			$result[$n]['checked'] =  Windphp::getDao('adminRolePriv')->checkPriv($roleid,$t['id']);
			$result[$n]['level'] = $t['level']-1;
			$result[$n]['parentid_node'] = ($t['parentid'])? ' class="child-of-node-'.$t['parentid'].'"' : '';
		}
		$str  = "<tr id='node-\$id' \$parentid_node>
					<td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$cname</td>
				</tr>";
		$tree->init($result);
		$categorys = $tree->get_tree(0, $str);
	
		$this->tpl->assign('roleid',$roleid);
		$this->tpl->assign('categorys',$categorys);
		$this->tpl->show();
	}
	
	
	public function beforAddEvent($info){
		empty($info['rolename']) and Response::showMessage('角色名称不能为空',$this->_refer);
		$check = $this->_cmodel->fetchOne(array('where'=>array('rolename'=>$info['rolename'])));
		$check and Response::showMessage($info['rolename'].' 已经存在，请修改！',$this->_refer);
		return $info;
	}
	
	
	public function afterDelEvent($id,$re){
		return Windphp::getDao('adminRolePriv')->delete(array('where'=>array('id'=>$id),'limit'=>1));
	}
	
	
	
}
?>