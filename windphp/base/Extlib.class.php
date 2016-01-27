<?php
/*
 * windphp v1.0
 * https://github.com/lijinhuan
 *
 * Copyright 2015 (c) 543161409@qq.com
 * GNU LESSER GENERAL PUBLIC LICENSE Version 3
 * http://www.gnu.org/licenses/lgpl.html
 *
 */

if(!defined('FRAMEWORK_PATH')) {
	exit('access error !');
}
  
class ExlibController  {
	
	public function __get($var) {
		return Core::setMagicGet($var, Core::$conf);
	}
	
	
}

?>
