<?php
/**
 * kill  `ps aux | grep 'swoole.php Http'  | grep -v grep|awk '{print $2}'`
 * for example: /app/php7/bin/php swoole.php Http|WebSocket|Tcp
 */
use Windphp\Windphp;
$root_path = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require  dirname(dirname($root_path)).DIRECTORY_SEPARATOR.'Windphp'.DIRECTORY_SEPARATOR.'Windphp.php';
Windphp::createSwooleApplication($root_path,$argv);
?>
