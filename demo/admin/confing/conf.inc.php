<?php
		
return array(
				'servers_hostname' => array(
	        				'produce' => array('20141128-4707'),
	        				'online' => array(''),
	        	),
				'image_url' => 'http://localhost/windphp/demo/admin/webroot/script/images/',
				'js_url' => 'http://localhost/windphp/demo/admin/webroot/script/js/',
				'css_url' => 'http://localhost/windphp/demo/admin/webroot/script/css/',
				'autokey' => 'f1fde2663fd11ef5cfb27a448b81e979',//不能删除应用唯一识别id
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