<?php
/**
 * Copyright (C) windphp framework
 * @todo ShopService
 */
namespace Services;

use Windphp\Service\IService;		
class ShopService extends IService {
	
	public  $shopInfo = array();
	 
	
	public function getShopInfo($id) {
		$id = intval($id);
		if(empty($id)) return $this->error('id empty');
		if(isset($this->shopInfo[$id]))return $this->shopInfo[$id];
		$cache_key = $id.'|shop_info';
		$this->shopInfo[$id] = $this->cache->get($cache_key);
		if($this->shopInfo[$id]===false) {
			$this->shopInfo[$id] = $this->shopDao->fetchOne(array('where'=>array('id'=>$id)));
			$this->cache->set($cache_key,$this->shopInfo[$id]);
		}
		if(empty($this->shopInfo[$id])) return $this->error('data delete',-2);
		return $this->success($this->shopInfo[$id]);
	}
	
	
		
}
?>
