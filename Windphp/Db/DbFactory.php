<?php
/**
 * @todo 数据库工厂类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-9-03
 */

namespace Windphp\Db;


class DbFactory {
	
	public static $dbObj = array();
	
	public static function getDb($dbConf) {
		$db_driver = 'Windphp\Db\Db'.ucfirst($dbConf['type']);
		$key = $db_driver.$dbConf['database'];
		if(!isset(self::$dbObj[$key])) {
			self::$dbObj[$key] = new $db_driver($dbConf);
		}
		return self::$dbObj[$key];
	}
	
	
	
}
