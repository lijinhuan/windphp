<?php
	//测试环境配置
	return array(
			'debug' => '1',
			'trace' => '1',
			'logsql' => '1',
			'log_err' => true,
			'app_url' => 'https://www.maiyatone.net/windphpdemo/',
			'swoole' => array(
        	 		'host' => '0.0.0.0',
					'http_port' => 9501,
					'websocket_port' => 9502,
					'tcp_port' => 9503,
					'reactor_num' => 2,
				    'worker_num' => 1,
				    'backlog' => 128,
				    'max_request' => 0,
				    'dispatch_mode' => 1,
					'task_worker_num' => 1,
					'daemonize'       => 1,
        	 ),
			'db' => array(
        	 		'vshop' => array(
						'type' => 'mysqli',
						'host' => 'localhost:3306',
						'username' => 'root',
						'password' => '123456',
						'database' => 'lee',
						'_charset' => 'utf8mb4'
				   ),
        	 ),
        	 'memd'=> array(
        			'default' => array(
        					'servers'=>array(
        							'host'=>'127.0.0.1',
        							'port'=>11211,
        							'height'=>75,
        							'auth' => array(
        									//'user' => 'test',
        									//'password'=>'test',
        							),
        					)
        			),
        	 ),
        	 'redis' => array(
        	 		'default' => array(
        	 				'servers'=>array(
        	 						'host'=>'127.0.0.1',
        	 						'port'=>6379,
        	 						'timeout'=>5,
        	 						'auth' => array(
        	 								//'user' => 'test',
        	 								//'password'=>'test',
        	 						),
        	 				)
        	 		),
        	 ),
		
		);
?>