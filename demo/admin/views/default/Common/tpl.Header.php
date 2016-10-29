<?php
/**
 * @name 首页模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="off">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>后台管理中心</title>
<link href="<!--{$system_conf['css_url']}-->reset.css" rel="stylesheet" type="text/css" />
<link href="<!--{$system_conf['css_url']}-->zh-cn-system.css" rel="stylesheet" type="text/css" />
<link href="<!--{$system_conf['css_url']}-->dialog.css" rel="stylesheet" type="text/css" />
<link href="<!--{$system_conf['css_url']}-->table_form.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<!--{$system_conf['js_url']}-->jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="<!--{$system_conf['js_url']}-->dialog.js"></script>
<script language="javascript" type="text/javascript" src="<!--{$system_conf['js_url']}-->admin_common.js"></script>
<!--{if !isset($index)}-->
<style>
	html.off, html.off body {
    		background:white;
	}
</style>
<!--{/if}-->
</head>
<body<!--{if isset($index)}--> class="objbody"<!--{/if}-->>
