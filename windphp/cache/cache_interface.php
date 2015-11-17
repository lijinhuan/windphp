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


interface cache_interface {

	public function __construct($conf);

	public function set($key,$value,$expire);
	
	public function update($key,$value,$expire);
	
	public function get($key);
	
	public function delete($key);
}



?>