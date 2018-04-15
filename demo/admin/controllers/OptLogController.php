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
class OptLogController extends CommonController {
		
	
	/**
	 * @todo 操作日志
	 */
	public function actionRun(){
		$fetch_param = array(
				'limit' => ShowPage::getPageQueryLimit(),
				'order' => 'id desc',
				'where' =>array()
		);
		if(Request::getInput('uid'))$fetch_param['where']['uid'] = intval(Request::getInput('uid'));
		if(Request::getInput('start_time'))$fetch_param['where']['addtime']['gte'] = strtotime(Request::getInput('start_time'));
		if(Request::getInput('end_time'))$fetch_param['where']['addtime']['lte'] = strtotime(Request::getInput('end_time'));
		$total_count = Windphp::getDao('userOptLog')->count($fetch_param['where']);
		$result = Windphp::getDao('userOptLog')->fetchAll($fetch_param);
		$result = $this->mergeExtend($result);
		$page = ShowPage::getPageStr($total_count, $this->_page,true,Config::getSystem('page_rows'));
		$this->tpl->assign('total_count',$total_count);
		$this->tpl->assign('page',$page);
		$this->tpl->assign('result',$result);
		$this->tpl->show();
	}
	
	
	
	/**
	 * @todo 合并扩展信息
	 */
	protected function mergeExtend($list){
		if(empty($list))return $list;
		foreach ($list as $k=>$v){
			$user =  Windphp::getDao('adminUser')->fetchOne(array('where'=>array('id'=>$v['uid'])));
			$v['realname'] = isset($user['realname'])?$user['realname']:'';
			$menu =  Windphp::getDao('adminMenu')->fetchOne(array('where'=>array('controller'=>$v['controller'],'action'=>$v['action'])));
			$v['menuname'] = isset($menu['name'])?$menu['name']:'';
			$list[$k] = $v;
		}
		return array_values($list);
	}
	
	
	
}
?>