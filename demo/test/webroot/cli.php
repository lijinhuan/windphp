<?php
//for example: /app/php7/bin/php cli.php CliUser Count 
use Windphp\Windphp;
$root_path = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require  dirname(dirname($root_path)).DIRECTORY_SEPARATOR.'Windphp'.DIRECTORY_SEPARATOR.'Windphp.php';
Windphp::createCliApplication($root_path,$argv);
?>
