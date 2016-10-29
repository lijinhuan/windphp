<?php
		
return array(
				'servers_hostname' => array(
	        				'produce' => array('MS-201503221318'),
	        				'online' => array(''),
	        	),
				'image_url' => 'http://www.backend.com/script/images/',
				'js_url' => 'http://www.backend.com/script/js/',
				'css_url' => 'http://www.backend.com/script/css/',
				'autokey' => 'e43988f0b41e3aef0d256acd230ae590',//不能删除应用唯一识别id
				'timezone' => 'Asia/Shanghai',
				'template_syntax' => 'smallTemplate',
				'template_theme' => 'default',
				'data_default_cache_time' => 900,
				'cache_type' => 'file',
				'maxpage' => 500,
				'page_rows' => 20,
				'support_cache' => array('memcache','redis','file'),
				'default_action' => 'Run',
		
		);
			
?>