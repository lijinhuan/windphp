<?php
//for example : /usr/local/php cli.php controllerName actionName param
use Windphp\Windphp;
!isset($argv) and exit('cli application');
$root_path = dirname(__DIR__). DIRECTORY_SEPARATOR;
require dirname(dirname($root_path)).DIRECTORY_SEPARATOR.'Windphp'.DIRECTORY_SEPARATOR.'Windphp.php';
Windphp::createCliApplication($root_path,$argv);
?>