<?php
/**
 * Copyright (C) windphp framework
 * @todo ShopDao
 */
namespace Daos;


use Windphp\Dao\IDao;
class ShopDao extends IDao {
		
	
	public function init(){
		$this->database = 'vshop';
		$this->table = 'shop';
	}
	
		
}
?>
