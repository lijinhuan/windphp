<?php
/**
 * Copyright (C) windphp framework
 * @todo IndexController
 */
namespace Controllers;
use Windphp\Controller\CController;
use Windphp\Web\Request;
use Windphp\Web\Response;
use Windphp\Misc\ShowPage;
use Windphp\Core\Config;
use Windphp\Misc\HttpClient;
use components\TestComponet;
use Windphp\Dao\IDao;
use Windphp\Windphp;
		
class FetchController extends CController {
		
	public function actionIndex(){
		exit();
		set_time_limit(0);
		
		$id = 242799913;//视频id
		$type = 2;//2服装，3美妆
		$total_num = 823;//一共多少条评论
		
		
		$max_page = ceil($total_num/100);
		//echo $max_page;exit;
		
		$idao = new IDao();
		$idao->database = 'vshop';
		$idao->table = 'comment_vest';
		
		
		
		for($page=1;$page<$max_page;$page++){
			$url = "http://www.meipai.com/medias/comments_timeline?page=$page&count=100&id=$id";
			echo $url."<br/>";
			$content = file_get_contents($url);
			$content = @json_decode($content,true);
			if($content){
				foreach ($content as $c){
					//echo $c['content']."<br/>";
					//判断是否已经在了
					$check = $idao->fetchOne(array('where'=>array(
							'content' => $c['content'],
							'type' => $type
					)));
					if(empty($check)) {
						$idao->insert(array('set'=>array(
								'content' => $c['content'],
								'type' => $type
						)));
						echo $c['content']."<br/>";
					}
					flush();
				}
				sleep(3);
			}
		}
		
	}
	
	
	public function actionChangeUser() {
		set_time_limit(0);
		$vshop_user_dao = Windphp::getIDao('user', 'vshop');
		$day_analysis_dao =  Windphp::getIDao('day_analysis', 'vshop');
		$month_analysis_dao =  Windphp::getIDao('month_analysis', 'vshop');
	
		/*
		$data = $vshop_user_dao->fetchAll(array());
		foreach ($data as $val) {
				$month = date('Ym',$val['regtime']);
				$day = date('Ymd',$val['regtime']);
				$vshop_user_dao->update(array(
						'where' => array('id'=>$val['id']),
						'set' => array(
								'date'=>$day,
								'month'=>$month
						)
				));
		}
		*/
		
		/*
		$data = $vshop_user_dao->fetchAll("SELECT count(*) as num,date FROM `user` group by date order by date asc");
		$totalUser = 0;
		foreach ($data as $val) {
			$totalUser += $val['num'];
			$day_analysis_dao->replace(array(
					'set' => array(
							'day' => $val['date'],
							'userRegister' => $val['num'],
							'totalUser' => $totalUser,
					)
			));
		}
		*/
		
		$data = $vshop_user_dao->fetchAll("SELECT count(*) as num,month FROM `user` group by month order by month asc");
		$totalUser = 0;
		foreach ($data as $val) {
			$totalUser += $val['num'];
			$month_analysis_dao->replace(array(
					'set' => array(
							'month' => $val['month'],
							'userRegister' => $val['num'],
							'totalUser' => $totalUser,
					)
			));
		}
		
	}
	
	
		
}
?>