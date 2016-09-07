<?php
use Windphp\Windphp;
$root_path = dirname(__DIR__). DIRECTORY_SEPARATOR;
require dirname(dirname($root_path)).DIRECTORY_SEPARATOR.'Windphp'.DIRECTORY_SEPARATOR.'Windphp.php';
Windphp::createWebApplication($root_path);
?>