<?php
// 调试模式: 0:关闭; 1：调试模式 2：开发模式
define('DEBUG',1);
define('TRACE', 0);
//加载框架文件
require './windphp/windphp.php';
//启动应用
core::run($conf);
?>

