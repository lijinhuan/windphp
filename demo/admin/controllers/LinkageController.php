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
class LinkageController extends CommonController {
		
	
	
	function __construct() {
		parent::__construct();
		$this->_cmodel = Windphp::getDao('adminLinkage');
	}
	
	/**
	 * @todo 联动菜单首页
	 */
	public function actionRun(){
		$keyid = 0;
		$where = array('keyid'=>$keyid);
		$result = $this->_cmodel->fetchAll(array('where'=>$where));
		$result = $this->checkLastNode($result,$keyid);
		$this->tpl->assign('result',$result);
		$this->tpl->show();
	}
	
	
	public function actionSubmenu(){
		$keyid = intval(Request::getInput('keyid'));
		$parentid = intval(Request::getInput('parentid'));
		empty($keyid) and Response::showMessage('参数有误',$this->_refer);
		$where = array('keyid'=>$keyid,'parentid'=>$parentid);
		$result = $this->_cmodel->fetchAll(array('where'=>$where));
		$result = $this->checkLastNode($result,$keyid);
		$this->tpl->assign('keyid',$keyid);
		$this->tpl->assign('parentid',$parentid);
		$this->tpl->assign('result',$result);
		$this->tpl->show();
	}
	
	
	protected function checkLastNode($result,$keyid){
		foreach ($result as $key=>$val){
			$result[$key]['is_last_node'] = $this->_cmodel->count(array('keyid'=>$keyid,'parentid'=>$val['id']));
		}
		return $result;
	}
	
	
	public function beforDelEvent($id){
		$data = $this->_cmodel->fetchOne(array('where'=>array('id'=>$id)));
		empty($data) and Response::showMessage('该菜单不存在',$this->_refer);
		if($data['keyid']==0){
			$this->_cmodel->delete(array('where'=>array('keyid'=>$id)));
		}else{
			$id_arr = $this->_cmodel->getAllSubMenu($id);
			$re = $this->_cmodel->delete(array(
					'where' => array('id'=>array('in'=>$id_arr))
			));
		}
	}
	
	
	
	
	
}
?>
