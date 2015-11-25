<?php
// 调试模式: 0:关闭; 1：调试模式 2：开发模式
define('DEBUG',1);
//页面调试信息： 0：关闭  1：开启
define('TRACE', 1);
//加载框架文件
require './windphp/windphp.php';
//启动应用
Core::run($conf);
?>


