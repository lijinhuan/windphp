<?php
/**
 * @name 首页模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<!--{template 'Header','Common'}-->
<div class="pad_10">
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
    <a class="add fb" href="javascript:edit('添加联动菜单','<!--{funcecho  \Windphp\Core\UrlRoute::getWebUrl('Linkage-Cadd')}-->')"><em>添加联动菜单</em></a>　    <a href="javascript:;" class="on"><em>联动菜单</em></a>    </div>
</div>
<div class="bk10"></div>
<form name="myform" controller="" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
		<tr>
		<th width="10%">ID</th>
		<th width="20%" align="left" >名称</th>
		<th width="30%" align="left" >描述</th>
		
		<th width="20%" >操作</th>
		</tr>
        </thead>
        <tbody>
		<!--{loop $result $linkage}-->
		<tr>
		<td width="10%" align="center"><!--{$linkage['id']}--></td>
		<td width="20%" ><!--{$linkage['name']}--></td>
		<td width="30%" ><!--{$linkage['description']}--></td>
		
		<td width="20%" class="text-c">
		    <a href="?controller=Linkage&action=Submenu&keyid=<!--{$linkage['id']}-->&parentid=0">管理子菜单</a> | 
		    <a href="javascript:void(0);" onclick="edit('编辑','<!--{funcecho  \Windphp\Core\UrlRoute::getWebUrl('Linkage-Cedit-id-'.$linkage['id'])}-->')">编辑</a> |
		   <a href="javascript:confirmurl('index.php?controller=Linkage&action=Cdel&id=<!--{$linkage['id']}-->', '是否删除?')">删除</a>
		</td>
		</tr>
		<!--{/loop}-->
</tbody>
</table>
</div>
</div>
</form>

<!--{template 'Footer','Common'}-->
