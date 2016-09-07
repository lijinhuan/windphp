<?php
/**
 * @todo debug类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Core;



class Debug {
	
	public static $systemStartTime;
	public static $startTime;
	public static $sqls = array('sqls'=>array());
	
	public static function startTime() {
		return self::$startTime = microtime(true);
	}
	
	
	public static function endTime() {
		return sprintf("%1\$.3f",microtime(true)-self::$startTime);
	}
	
	public static function systemStartTime() {
		return self::$systemStartTime = microtime(true);
	}
	
	public static function SystemEndTime(){
		return sprintf("%1\$.3f",microtime(true)-self::$systemStartTime);
	}
	
	
	public static function usageMemory(){
		return (round(memory_get_usage()/1024,2)) .'kb';
	}
	
	
	public static function addSql($sql) {
		if(count(self::$sqls)>1000) return self::$sqls;
		return self::$sqls['sqls'][] = $sql;
	}
	
	
	
	public static function show($debugData=array()) {
		echo "<div style='border:2px solid green;padding:20px;border-radius:10px;margin:10px;'>";
		echo 'time: '.self::SystemEndTime().' &nbsp; &nbsp; &nbsp; memoery:'. self::usageMemory();
		echo "<br/><pre>";
		print_r(array('include_files'=>get_included_files()));
		echo "</pre>";
		echo "<br/><pre>";
		print_r(self::$sqls);
		echo "</pre>";
		if($debugData) {
			echo "<br/><pre>";
			print_r($debugData);
			echo "</pre>";
		}
		echo "</div>";
	}
	
	
}
